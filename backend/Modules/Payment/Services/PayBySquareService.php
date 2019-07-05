<?php


namespace Modules\Payment\Services;


use Modules\Payment\Repositories\PaymentOptionsRepository;

class PayBySquareService
{
    private $sum;
    private $currency;
    private $variableSymbol;
    private $date;
    private $constantSymbol;
    private $specificSymbol;
    private $note;
    private $iban;
    private $swift;

    private $paymentMethodId;

    protected $paymentOptionsRepository;
    protected $variableSymbolService;

    public function __construct(PaymentOptionsRepository $paymentOptionsRepository, VariableSymbolService $variableSymbolService)
    {
        $this->paymentOptionsRepository = $paymentOptionsRepository;
        $this->variableSymbolService = $variableSymbolService;
        $this->paymentMethodId = 3; // PAY BY SQUARE in payment_methods table
    }

    public function getPayBySquareDetails()
    {
        return $this->paymentOptionsRepository->getPaymentMethodDetails($this->paymentMethodId);
    }

    public function getQRCode($request)
    {
        try {
            $frequency = $request['frequency'];
            $this->sum = $request['sum'];
            $payBySquareDetails = $this->getPayBySquareDetails()[$frequency];
            $this->currency = $payBySquareDetails->currency;
            $this->variableSymbol = $this->variableSymbolService->getByPortalUser();
            $this->date = date('Ymd');
            $this->constantSymbol = $payBySquareDetails->constantSymbol;
            $this->specificSymbol = $payBySquareDetails->specificSymbol;
            $this->note = $payBySquareDetails->note;
            $this->iban = $payBySquareDetails->iban;
            $this->swift = $payBySquareDetails->swift;

            $paymentData = implode('\t', array(
                0 => '',
                1 => '1',
                2 => implode('\t', array(
                    true,
                    $this->sum,
                    $this->currency,
                    $this->variableSymbol,
                    $this->constantSymbol,
                    $this->specificSymbol,
                    '',
                    $this->note,
                    '1',
                    $this->iban,
                    $this->swift,
                    '0',
                    '0'
                ))
            ));
            $paymentData = strrev(hash("crc32b", $paymentData, TRUE)) . $paymentData;

            $process = proc_open("/usr/bin/xz '--format=raw' '--lzma1=lc=3,lp=0,pb=2,dict=128KiB' '-c' '-'", [0 => ["pipe", "r"], 1 => ["pipe", "w"]], $p);
            fwrite($p[0], $$paymentData);
            fclose($p[0]);
            $output = stream_get_contents($p[1]);
            fclose($p[1]);
            proc_close($process);

            $paymentData = bin2hex("\x00\x00" . pack("v", strlen($paymentData)) . $output);
            $base = "";

            for ($i = 0; $i < strlen($paymentData); $i++) {
                $base .= str_pad(base_convert($paymentData[$i], 16, 2), 4, "0", STR_PAD_LEFT);
            }

            $length = strlen($base);

            $r = $length % 5;
            if ($r > 0) {
                $p = 5 - $r;
                $base .= str_repeat("0", $p);
                $length += $p;
            }
            $length = $length / 5;
            $paymentData = str_repeat("_", $length);
            for ($i = 0; $i < $length; $i += 1) {
                $d[$i] = "0123456789ABCDEFGHIJKLMNOPQRSTUV"[bindec(substr($base, $i * 5, 5))];
            }

            if (!empty($paymentData)) {
                $u = '/chart?chs=200x200&cht=qr&chld=L|0&choe=UTF-8&chl=' . $paymentData;
                $sock = @fsockopen("chart.googleapis.com", 443, $err, $r, 1);
                if ($sock) {
                    $head = "GET " . $u . " HTTP/1.0\r\n";
                    $head .= "Host: chart.googleapis.com\r\n";
                    $head .= "Connection: close\r\n\r\n";
                    fwrite($sock, $head);
                    $e = '';
                    $qrCode = "";
                    do {
                        $e .= fgets($sock, 128);
                    } while (strpos($e, "\r\n\r\n") === false);
                    while (!feof($sock)) {
                        $qrCode .= fgets($sock, 4096);
                    }
                    fclose($sock);
                }
            }
        } catch (\Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }

        return response($qrCode, Response::HTTP_OK)
            ->header('Content-Type', 'image/png');

    }
}