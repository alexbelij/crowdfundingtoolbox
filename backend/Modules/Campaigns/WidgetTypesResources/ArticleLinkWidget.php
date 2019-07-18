<?php


namespace Modules\Campaigns\WidgetTypesResources;


class ArticleLinkWidget extends WidgetTypeResource
{
    public function initDesktop()
    {
        return $this->init(
            $this->generalStyles('auto', 'auto', 'relative', '', 'inline', ''),
            $this->bodyContainer('auto', array(
                'top' => 0,
                'right' => 0,
                'bottom' => 0,
                'left' => 0
            ), 'relative', '', '', '', '', array(
                'width' => 'auto'
            )),
            $this->textContainer('auto', array(
                'top' => 0,
                'right' => 0,
                'bottom' => 0,
                'left' => 0
            ), 'relative', '', '', '', '', array(
                'width' => 'auto'
            )),
            $this->bodyContainer(0, array(
                'top' => 0,
                'right' => 0,
                'bottom' => 0,
                'left' => 0
            ), 'relative', '', '', '', '', array(
                'width' => 0
            ))
        );
    }

    public function initTablet()
    {
        return $this->init(
            $this->generalStyles('auto', 'auto', 'relative', '', 'inline', ''),
            $this->bodyContainer('auto', array(
                'top' => 0,
                'right' => 0,
                'bottom' => 0,
                'left' => 0
            ), 'relative', '', '', '', '', array(
                'width' => 'auto'
            )),
            $this->textContainer('auto', array(
                'top' => 0,
                'right' => 0,
                'bottom' => 0,
                'left' => 0
            ), 'relative', '', '', '', '', array(
                'width' => 'auto'
            )),
            $this->bodyContainer(0, array(
                'top' => 0,
                'right' => 0,
                'bottom' => 0,
                'left' => 0
            ), 'relative', '', '', '', '', array(
                'width' => 0
            ))
        );
    }

    public function initMobile()
    {
        return $this->init(
            $this->generalStyles('auto', 'auto', 'relative', '', 'inline', ''),
            $this->bodyContainer('auto', array(
                'top' => 0,
                'right' => 0,
                'bottom' => 0,
                'left' => 0
            ), 'relative', '', '', '', '', array(
                'width' => 'auto'
            )),
            $this->textContainer('auto', array(
                'top' => 0,
                'right' => 0,
                'bottom' => 0,
                'left' => 0
            ), 'relative', '', '', '', '', array(
                'width' => 'auto'
            )),
            $this->bodyContainer(0, array(
                'top' => 0,
                'right' => 0,
                'bottom' => 0,
                'left' => 0
            ), 'relative', '', '', '', '', array(
                'width' => 0
            ))
        );
    }

    public function result()
    {
        return array(
            'desktop' => $this->initDesktop(),
            'tablet' => $this->initTablet(),
            'mobile' => $this->initMobile()
        );
    }

}