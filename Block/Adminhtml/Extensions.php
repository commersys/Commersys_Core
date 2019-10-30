<?php
namespace Commersys\Core\Block\Adminhtml;

use Magento\Framework\Data\Form\Element\AbstractElement;


class Extensions extends \Magento\Config\Block\System\Config\Form\Fieldset
{

    /**
     * @var \Magento\Framework\Module\ModuleListInterface
     */
    private $moduleList;

    /**
     * @var \Magento\Framework\View\Element\BlockInterface|null
     */
    private $_fieldRenderer;

    /**
     * @var \Magento\Framework\Module\FullModuleList
     */
    protected $fullModuleList;

    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Framework\View\Helper\Js $jsHelper,
        \Magento\Framework\Module\ModuleListInterface $moduleList,
        \Magento\Framework\Module\FullModuleList $fullModuleList,
        array $data = []
    )
    {
        parent::__construct($context, $authSession, $jsHelper, $data);

        $this->moduleList = $moduleList;
        $this->fullModuleList = $fullModuleList;

    }

    /**
     * Render fieldset html
     *
     * @param AbstractElement $element
     *
     * @return string
     */
    public function render(AbstractElement $element)
    {
        $html = $this->_getHeaderHtml($element);

        $commersysExtensionsArray = $this->getExtensionsData();
        $allModules = $this->fullModuleList->getAll();
        $uniqueModules = $this->recursive_array_intersect_key($allModules,$commersysExtensionsArray);
        $html .= '<table border="1" style="text-align: center;"><thead><tr style="height: 42px;"><th>Module Name</th><th>Current Version</th><th>Recommended Version</th></tr></thead><tbody>';
        foreach ($uniqueModules as $key => $value){
            $html .= '<tr><td style="padding: 11px;">'.$key . '</td>';
            if($allModules[$key]['setup_version'] < $commersysExtensionsArray[$key]['setup_version']){
                $flag = 'red';
            }else{ $flag = 'green' ;}
            $html .= '<td style="color:'.$flag.';padding: 11px;">'.$allModules[$key]['setup_version'].'</td>';
            $html .= '<td style="padding: 11px;">'.$commersysExtensionsArray[$key]['setup_version'].'</td></tr>';
        }
        $html .= '<tbody></table>';

        $html .= $this->_getFooterHtml($element);

        return $html;
    }

    /**
     * @param array $array1
     * @param array $array2
     * @return array
     */
    protected function recursive_array_intersect_key(array $array1, array $array2) {
        $array1 = array_intersect_key($array1, $array2);
        foreach ($array1 as $key => &$value) {
//            if (is_array($value)) {
//                $value = recursive_array_intersect_key($value, $array2[$key]);
//            }
        }
        return $array1;
    }

    /**
     * @return mixed
     */
    protected function getExtensionsData()
    {
        //  Initiate curl
        $ch = curl_init();
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Set the url
        curl_setopt($ch, CURLOPT_URL,'http://blogs.demo.commersys.com/commersys_extensions.json');
        // Execute
        $result=curl_exec($ch);
        // Closing
        curl_close($ch);

        return json_decode($result, true);
    }

}

