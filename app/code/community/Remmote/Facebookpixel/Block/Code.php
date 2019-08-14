<?php
/**
 * @extension   Remmote_Facebookpixel
 * @author      Remmote    
 * @copyright   2016 - Remmote.com
 * @descripion  Code Block
 */
class Remmote_Facebookpixel_Block_Code extends Mage_Core_Block_Template {

	/**
	 * Renders pixel code if module is enabled
	 * @return [type]
	 * @author edudeleon
	 * @date   2016-10-10
	 */
	public function _toHtml()
    {
        if (Mage::helper('remmote_facebookpixel')->isEnabled()){
            return parent::_toHtml();
        }
    }

    /**
     * Return Facebook Pixel Id
     * @return [type]
     * @author edudeleon
     * @date   2016-10-10
     */
    public function getPixelId(){
    	return Mage::helper('remmote_facebookpixel')->getPixelId();
    }

    /**
     * Get store current section
     * @return [type]
     * @author edudeleon
     * @date   2016-10-11
     */
    private function _getSection(){
        $pageSection  = Mage::app()->getFrontController()->getAction()->getFullActionName(); 
        return  $pageSection; 
    }

    /**
     * Get current store currency
     * @return [type]
     * @author edudeleon
     * @date   2016-10-11
     */
    private function _getStoreCurrency(){
        return Mage::app()->getStore()->getCurrentCurrencyCode();
    }

    /**
     * Return View Content event track
     * @return [type]
     * @author edudeleon
     * @date   2016-10-11
     */
    public function getViewContentEvent(){
        $pageSection = $this->_getSection();

        //Check if event is enabled
        if(Mage::helper('remmote_facebookpixel')->viewContentEnabled()){ 
            if($pageSection == 'catalog_product_view'){
                return "fbq('track', 'ViewContent');";
            }
        }
    }

    /**
     * Return Search event track
     * @return [type]
     * @author edudeleon
     * @date   2016-10-11
     */
    public function getSearchEvent(){
        $pageSection = $this->_getSection();

        //Check if event is enabled
        if(Mage::helper('remmote_facebookpixel')->searchEnabled()){
            if($pageSection == 'catalogsearch_result_index' || $pageSection == 'catalogsearch_advanced_result'){ 
                return "fbq('track', 'Search');";
            }
        }
    }

    /**
     * Return AddToCart event track
     * @return [type]
     * @author edudeleon
     * @date   2016-10-11
     */
    public function getAddToCartEvent(){
        $pageSection = $this->_getSection();

        //Check if event is enabled
        if(Mage::helper('remmote_facebookpixel')->addToCartEnabled()){

            $pixelEvent = Mage::getModel('core/session')->getPixelAddToCart();
            if($pixelEvent){
                //Unset event
                Mage::getModel('core/session')->unsPixelAddToCart();
                return "fbq('track', 'AddToCart');";   
            }            
        }
    }

    /**
     * Return AddToWishlist event track
     * @return [type]
     * @author edudeleon
     * @date   2016-10-11
     */
    public function getAddToWishlistEvent(){
        $pageSection = $this->_getSection();

        //Check if event is enabled
        if(Mage::helper('remmote_facebookpixel')->addToWhishlistEnabled()){            
            $pixelEvent = Mage::getModel('core/session')->getPixelAddToWishlist();
            if($pixelEvent){
                //Unset event
                Mage::getModel('core/session')->unsPixelAddToWishlist();
                return "fbq('track', 'AddToWishlist');";   
            }
        }
    }

    /**
     * Return InitiateCheckout event track
     * @return [type]
     * @author edudeleon
     * @date   2016-10-11
     */
    public function getInitiateCheckoutEvent(){
        $pageSection = $this->_getSection();

        //Check if event is enabled
        if(Mage::helper('remmote_facebookpixel')->initiateCheckoutEnabled()){
            if ($pageSection == 'checkout_onepage_index' || $pageSection == 'onestepcheckout_index_index' || $pageSection == 'opc_index_index'){
                return "fbq('track', 'InitiateCheckout');";
            }
        }
    }

    /**
     * Return AddPaymentInfo event track
     * @return [type]
     * @author edudeleon
     * @date   2016-10-11
     */
    public function getAddPaymentInfoEvent(){
        $pageSection = $this->_getSection();

        //Check if event is enabled
        if(Mage::helper('remmote_facebookpixel')->addPaymentInfoEnabled()){
            $pixelEvent = Mage::getModel('core/session')->getPixelPaymentInfo();
            if($pixelEvent){
                //Unset event
                Mage::getModel('core/session')->unsPixelPaymentInfo();

                return "fbq('track', 'AddPaymentInfo');";
            }
        }
    }

    /**
     * Return Purchase event track
     * @return [type]
     * @author edudeleon
     * @date   2016-10-11
     */
    public function getPurchaseEvent(){
        $pageSection        = $this->_getSection();
        $currentCurrency    = $this->_getStoreCurrency();

        //Check if event is enabled
        if(Mage::helper('remmote_facebookpixel')->purchaseEnabled()){
        
            $pixelEvent = Mage::getModel('core/session')->getPixelPurchase();
            if($pixelEvent){
                //Unset event
                Mage::getModel('core/session')->unsPixelPurchase();

                $orderId            = Mage::getSingleton('checkout/session')->getLastRealOrderId();
                $order              = Mage::getModel('sales/order')->loadByIncrementId($orderId);
                $orderGrandTotal    = number_format($order->getGrandTotal(),2);
                
                return "fbq('track', 'Purchase', {
                    value:          '".$orderGrandTotal."',
                    currency:       '".$currentCurrency."'
                });";
            }
        }
    }  

    /**
     * Return Lead event track
     * @return [type]
     * @author edudeleon
     * @date   2016-10-11
     */
    public function getLeadEvent(){
        $pageSection = $this->_getSection();

        //Check if event is enabled
        if(Mage::helper('remmote_facebookpixel')->leadEnabled()){            
            $pixelEvent = Mage::getModel('core/session')->getPixelLead();
            if($pixelEvent){
                //Unset event
                Mage::getModel('core/session')->unsPixelLead();
                
                return "fbq('track', 'Lead');";
            }
        }
    }

    /**
     * Return CompleteRegistration event track
     * @return [type]
     * @author edudeleon
     * @date   2016-10-11
     */
    public function getCompleteRegistrationEvent(){
        $pageSection = $this->_getSection();

        //Check if event is enabled
        if(Mage::helper('remmote_facebookpixel')->completeRegistrationEnabled()){
            $pixelEvent = Mage::getModel('core/session')->getPixelCompleteRegistration();
            if($pixelEvent){
                //Unset event
                Mage::getModel('core/session')->unsPixelCompleteRegistration();
                
                return "fbq('track', 'CompleteRegistration');";
            }
        }
    }
}