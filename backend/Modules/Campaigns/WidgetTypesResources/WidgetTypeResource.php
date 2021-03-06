<?php


namespace Modules\Campaigns\WidgetTypesResources;


class WidgetTypeResource implements WidgetTypeInterface
{

    public function generalStyles($width, $height, $position, $fixedSettings, $display, $padding)
    {
        return array(
            'width' => $width,
            'height' => $height,
            'maxWidth' => '100%',
            'position' => $position,
            'fixedSettings' => $fixedSettings, // must be an array
            'display' => $display,
            'padding' => $padding // must be an array
        );
    }

    public function bodyContainer($width, $margin, $position, $top, $right, $bottom, $left, $text)
    {
        return array(
            'width' => $width,
            'height' => '100%',
            'margin' => $margin, // must be an array
            'position' => $position,
            'top' => $top,
            'right' => $right,
            'bottom' => $bottom,
            'left' => $left,
            'text' => $text
        );
    }

    public function textContainer($width, $margin, $position, $top, $right, $bottom, $left, $text)
    {
        return array(
            'width' => $width,
            'margin' => $margin, // must be an array
            'position' => $position,
            'top' => $top,
            'right' => $right,
            'bottom' => $bottom,
            'left' => $left,
            'text' => $text // must be an array
        );
    }

    public function buttonContainer($width, $position, $top, $right, $bottom, $left, $textAlign, $button)
    {
        return array(
            'width' => $width,
            'position' => $position,
            'top' => $top,
            'right' => $right,
            'bottom' => $bottom,
            'left' => $left,
            'textAlign' => $textAlign,
            'button' => $button // must be an array
        );
    }

    public function continueReadingButtonContainer($width, $position, $margin, $button)
    {
        // Only for locked article widget
        return array(
            'width' => $width,
            'position' => $position,
            'margin' => $margin,
            'button' => $button
        );
    }

    public function articleWidgetText($text)
    {
        return $text;
    }

    public function init($generalStyles, $bodyContainer, $textContainer, $buttonContainer, $continueReadingButtonContainer, $articleWidgetText)
    {
        if ($continueReadingButtonContainer !== null) {
            return array_merge($generalStyles, array(
                'bodyContainer' => $bodyContainer,
                'textContainer' => $textContainer,
                'buttonContainer' => $buttonContainer,
                'continueReadingButtonContainer' => $continueReadingButtonContainer
            ));
        } else if ($articleWidgetText !== null) {
            return array_merge($generalStyles, array(
                'bodyContainer' => $bodyContainer,
                'textContainer' => $textContainer,
                'buttonContainer' => $buttonContainer,
                'articleWidgetText' => $articleWidgetText
            ));
        }
        return array_merge($generalStyles, array(
            'bodyContainer' => $bodyContainer,
            'textContainer' => $textContainer,
            'buttonContainer' => $buttonContainer
        ));
    }

    public function initDesktop()
    {
        return $this->init(
            $this->generalStyles('', '', '', '', '', ''),
            $this->bodyContainer('', '', '', '', '', '', '', ''),
            $this->textContainer('', '', '', '', '', '', '', ''),
            $this->buttonContainer('', '', '', '', '', '', '', ''),
            null,
            null
        );
    }

    public function initTablet()
    {
        return $this->init(
            $this->generalStyles('', '', '', '', '', ''),
            $this->bodyContainer('', '', '', '', '', '', '', ''),
            $this->textContainer('', '', '', '', '', '', '', ''),
            $this->buttonContainer('', '', '', '', '', '', '', ''),
            null,
            null
        );
    }

    public function initMobile()
    {
        return $this->init(
            $this->generalStyles('', '', '', '', '', ''),
            $this->bodyContainer('', '', '', '', '', '', '', ''),
            $this->textContainer('', '', '', '', '', '', '', ''),
            $this->buttonContainer('', '', '', '', '', '', '', ''),
            null,
            null
        );
    }
}