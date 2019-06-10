<?php


namespace Modules\Campaigns\Database\Seeders;


use Illuminate\Database\Seeder;
use Modules\Campaigns\Services\CampaignService;

class CreateDummyCampaignSeeder extends Seeder
{

    private $campaignService;


    public function __construct()
    {

        $this->campaignService = new CampaignService();
    }


    function run()
    {
        $json = str_replace('&quot;', '"', $this->dummyRequest);
        $this->campaignService->create(json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $json), true));
    }

    private $test = '{"a":1,"b":2,"c":3,"d":4,"e":5}';

    private $dummyRequest = '
      {  
           "id":0,
           "active":false,
           "name":"test campaign",
           "description":"created using CreateDummyCampaignSeeder",
           "headline_text":"",
           "targeting":{  
              "signed_status":{  
                 "signed":{  
                    "active":true
                 },
                 "not_signed":{  
                    "active":false
                 }
              },
              "support":{  
                 "one_time":{  
                    "active":true,
                    "older_than":{  
                       "active":true,
                       "value":30
                    },
                    "not_older_than":{  
                       "active":false,
                       "value":60
                    },
                    "min":{  
                       "active":false,
                       "value":50
                    },
                    "max":{  
                       "active":false,
                       "value":100
                    }
                 },
                 "monthly":{  
                    "active":false,
                    "older_than":{  
                       "active":true,
                       "value":50
                    },
                    "not_older_than":{  
                       "active":false,
                       "value":90
                    },
                    "min":{  
                       "active":false,
                       "value":5
                    },
                    "max":{  
                       "active":false,
                       "value":15
                    }
                 },
                 "not_supporter":{  
                    "active":false
                 }
              },
              "read_articles":{  
                 "today":{  
                    "active":false,
                    "min":0,
                    "max":5
                 },
                 "week":{  
                    "active":false,
                    "min":10,
                    "max":25
                 },
                 "month":{  
                    "active":true,
                    "min":20,
                    "max":0
                 }
              },
              "registration":{  
                 "before":{  
                    "active":false,
                    "date":"2019-6-10"
                 },
                 "after":{  
                    "active":false,
                    "date":"2019-6-10"
                 }
              },
              "url":{  
                 "specific":false,
                 "list":[  
                    {  
                       "id":0,
                       "path":"https://www.postoj.sk"
                    },
                    {  
                       "id":0,
                       "path":"https://www.postoj.sk/politika"
                    }
                 ]
              }
           },
           "promote_settings":{  
              "start_date_value":"2019-6-10",
              "start_date_json":{  
                 "year":2019,
                 "month":6,
                 "day":10
              },
              "is_end_date":true,
              "end_date_value":"2019-6-11",
              "end_date_json":{  
                 "year":2019,
                 "month":6,
                 "day":11
              },
              "donation_goal_value":""
           },
           "payment_settings":{  
              "payment_type":"both",
              "design":{  
                 "background_color":"#fff",
                 "padding":{  
                    "top":"10",
                    "right":"25",
                    "bottom":"10",
                    "left":"25"
                 },
                 "margin":{  
                    "top":"15",
                    "right":"10",
                    "bottom":"15",
                    "left":"10"
                 },
                 "width":"100%",
                 "height":"auto"
              },
              "monthly_prices":{  
                 "custom_price":false,
                 "count_of_options":2,
                 "options":[  
                    {  
                       "value":30
                    },
                    {  
                       "value":20
                    }
                 ],
                 "benefit":{  
                    "active":true,
                    "text":"S podporou 10 € a viac mesačne sa môžete stať členom Klubu Postoj a získať naše špeciálne tlačené vydanie.",
                    "value":10
                 }
              },
              "once_prices":{  
                 "custom_price":false,
                 "count_of_options":2,
                 "options":[  
                    {  
                       "value":30
                    },
                    {  
                       "value":20
                    }
                 ],
                 "benefit":{  
                    "active":true,
                    "text":"S podporou 60 € a viac ročne sa môžete stať členom Klubu Postoj a získať naše špeciálne tlačené vydanie.",
                    "value":10
                 }
              },
              "default_price":{  
                 "active":true,
                 "value":30,
                 "styles":{  
                    "background":"#3B3232",
                    "color":"#FFFFFF"
                 }
              }
           },
           "widget_settings":{  
              "general":{  
                 "fontSettings":{  
                    "fontFamily":"Roboto",
                    "fontWeight":"Bold",
                    "alignment":"center",
                    "color":"#FFFFFF",
                    "fontSize":24
                 },
                 "background":{  
                    "type":"image-overlay",
                    "image":{  
                       "id":10,
                       "url":""
                    },
                    "color":"#1F4F7B",
                    "opacity":33
                 },
                 "text_margin":{  
                    "top":"0",
                    "right":"auto",
                    "bottom":"0",
                    "left":"auto"
                 },
                 "text_display":"block",
                 "text_background":"#ffffff",
                 "common_text":{  
                    "active":true,
                    "value":"Lorem ipsum bla bla"
                 }
              },
              "call_to_action":{  
                 "default":{  
                    "padding":{  
                       "top":"10",
                       "right":"25",
                       "bottom":"10",
                       "left":"25"
                    },
                    "margin":{  
                       "top":"0",
                       "right":"auto",
                       "bottom":"0",
                       "left":"auto"
                    },
                    "fontSettings":{  
                       "fontFamily":"Roboto",
                       "fontWeight":"bold",
                       "alignment":"center",
                       "color":"#FFFFFF",
                       "fontSize":24
                    },
                    "display":"block",
                    "design":{  
                       "fill":{  
                          "active":true,
                          "color":"#B71100",
                          "opacity":100
                       },
                       "border":{  
                          "active":false,
                          "color":"#B71100",
                          "size":2,
                          "opacity":0
                       },
                       "shadow":{  
                          "active":false,
                          "color":"#777",
                          "x":2,
                          "y":2,
                          "b":2,
                          "opacity":15
                       },
                       "radius":{  
                          "active":true,
                          "tl":3,
                          "tr":4,
                          "br":2,
                          "bl":1
                       }
                    }
                 },
                 "hover":{  
                    "type":"fade",
                    "fontSettings":{  
                       "fontWeight":"bold",
                       "color":"#FFFFFF"
                    },
                    "design":{  
                       "fill":{  
                          "active":true,
                          "color":"#B71100",
                          "opacity":100
                       },
                       "border":{  
                          "active":false,
                          "color":"#B71100",
                          "size":2,
                          "opacity":0
                       },
                       "shadow":{  
                          "active":false,
                          "color":"#777",
                          "x":2,
                          "y":2,
                          "b":2,
                          "opacity":15
                       }
                    }
                 }
              }
           }
        }
     ';
}