<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@myflyingbox.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade your module to newer
 * versions in the future.
 *
 * @author    MyFlyingBox <contact@myflyingbox.com>
 * @copyright 2016 MyFlyingBox
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * @version   1.0
 *
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class AdminParcelController extends ModuleAdminController
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->bootstrap = true;

        // The below attributes are used for many automatic naming conventions
        $this->table = 'lce_parcels'; // Table containing the records
        $this->className = 'LceParcel'; // Class of the object managed by this controller
        $this->context = Context::getContext();
        $this->identifier = 'id_parcel'; // The unique identifier column for the corresponding object

        parent::__construct();
    }

    public function renderForm()
    {
        $countries = array();
        $countries[0] = array('country_code' => '', 'name' => '-');
        foreach (Country::getCountries($this->context->language->id) as $c) {
            $countries[$c['iso_code']] = array('country_code' => $c['iso_code'], 'name' => $c['name']);
        }
        $currencies = array(
            'EUR' => array('currency_code' => 'EUR', 'name' => 'EUR'),
            'USD' => array('currency_code' => 'USD', 'name' => 'USD'),
        );

        $this->multiple_fieldsets = true;
        $this->fields_form = array();
        $this->fields_form[] = array('form' => array(
            'legend' => array(
                'title' => $this->l('Dimensions')
            ),
            'input' => array(
                array(
                    'type' => 'hidden',
                    'name' => 'shipment_id'
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Length (cm):'),
                    'name' => 'length',
                    'size' => 5,
                    'class' => 'fixed-width-sm',
                    'required' => true
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Width (cm):'),
                    'name' => 'width',
                    'size' => 5,
                    'class' => 'fixed-width-sm',
                    'required' => true
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Height (cm):'),
                    'name' => 'height',
                    'size' => 5,
                    'class' => 'fixed-width-sm',
                    'required' => true 
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Weight (kg):'),
                    'name' => 'weight',
                    'size' => 5,
                    'class' => 'fixed-width-sm',
                    'required' => true
                ),
            ),
        ));

        $this->fields_form[] = array('form' => array(
            'legend' => array(
                'title' => $this->l('Customs'),
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Value:'),
                    'name' => 'value',
                    'size' => 5,
                    'class' => 'fixed-width-sm',
                    'desc' => $this->l('Declared value of the content.')
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Currency:'),
                    'desc' => $this->l('Currency code for the value.'),
                    'name' => 'currency',
                    'options' => array(
                        'query' => $currencies,
                        'id' => 'currency_code',
                        'name' => 'name',
                    ),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Description:'),
                    'name' => 'description',
                    'size' => 40,
                    'class' => 'fixed-width-xl',
                    'desc' => $this->l('Description of the goods.')
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Country of origin:'),
                    'desc' => $this->l('Country code of the origin of the products in the package.'),
                    'name' => 'country_of_origin',
                    'options' => array(
                        'query' => $countries,
                        'id' => 'country_code',
                        'name' => 'name',
                    ),
                ),
            ),
        ));

        $this->fields_form[] = array('form' => array(
            'legend' => array(
                'title' => $this->l('Ad Valorem Insurance')
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Value to insure:'),
                    'name' => 'value_to_insure',
                    'size' => 5,
                    'class' => 'fixed-width-sm',
                    'desc' => $this->l('You can leave blank if you do not intend to purchase insurance. Maximum 2000€ total per shipment.'),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Currency:'),
                    'desc' => $this->l('Currency code for the value to insure.'),
                    'name' => 'insured_value_currency',
                    'options' => array(
                        'query' => $currencies,
                        'id' => 'currency_code',
                        'name' => 'name',
                    ),
                ),
            ),
        ));

        $this->fields_form[] = array('form' => array(
            'legend' => array(
                'title' => $this->l('References')
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Shipper reference:'),
                    'name' => 'shipper_reference',
                    'size' => 5,
                    'class' => 'fixed-width-lg',
                    'desc' => $this->l('Your reference. May be printed on the label, depending on the carrier.'),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Recipient reference:'),
                    'name' => 'recipient_reference',
                    'size' => 5,
                    'class' => 'fixed-width-lg',
                    'desc' => $this->l('Recipient\'s reference may be printed on the label, depending on the carrier.'),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Customer reference:'),
                    'name' => 'customer_reference',
                    'size' => 5,
                    'class' => 'fixed-width-lg',
                    'desc' => $this->l('If your customer is not the recipient, specific reference for the customer.'),
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'button btn btn-primary pull-right',
            ),
        ));

        // Loading object, if possible; returning empty object otherwise
        if (!($obj = $this->loadObject(true))) {
            return;
        }

        // If we have a new object, we initialize default values
        if (!$obj->id) {
            $shipment = new LceShipment((int) Tools::getValue('id_shipment'));
            $this->fields_value['id_shipment'] = $shipment->id;
        }

        $this->show_toolbar = false;

        return parent::renderForm();
    }

    // Creating a new parcel, from parameters submitted in Ajax
    public function ajaxProcessSaveForm()
    {
        if ((int) Tools::getValue('id_parcel') > 0) {
            $parcel = new LceParcel((int) Tools::getValue('id_parcel'));
        } else {
            $parcel = new LceParcel();
        }

        $shipment = new LceShipment((int) Tools::getValue('id_shipment'));

        $parcel->id_shipment = $shipment->id;
        // Dimensions
        $parcel->length = (int) Tools::getValue('length');
        $parcel->width = (int) Tools::getValue('width');
        $parcel->height = (int) Tools::getValue('height');
        $parcel->weight = (float) Tools::getValue('weight');
        // References
        $parcel->shipper_reference = Tools::getValue('shipper_reference');
        $parcel->recipient_reference = Tools::getValue('recipient_reference');
        $parcel->customer_reference = Tools::getValue('customer_reference');
        // Customs
        $parcel->value = Tools::getValue('value');
        $parcel->currency = Tools::getValue('currency');
        $parcel->description = Tools::getValue('description');
        $parcel->country_of_origin = Tools::getValue('country_of_origin');
        // Insurance
        $parcel->value_to_insure = Tools::getValue('value_to_insure');
        $parcel->insured_value_currency = Tools::getValue('insured_value_currency');


        if ($parcel->id) {
            $action = 'save';
        } else {
            $action = 'add';
        }

        if ($parcel->validateFields(false) && $parcel->{$action}()) {
            $shipment->invalidateOffer();
            die(json_encode($parcel));
        } else {
            header('HTTP/1.0 422 Unprocessable Entity');
            die(json_encode(array('error' => $this->l('Parcel could not be saved.'))));
        }
    }

    // Creating a new parcel, from parameters submitted in Ajax
    public function ajaxProcessDeleteParcel()
    {
        $parcel = new LceParcel((int) Tools::getValue('id_parcel'));

        if (!$parcel) {
            header('HTTP/1.0 404 Not Found');
            die(json_encode(array('error' => $this->l('Parcel not found.'))));
        }

        $shipment = new LceShipment($parcel->id_shipment);
        if ($shipment->api_order_uuid) {
            header('HTTP/1.0 422 Unprocessable Entity');
            die(json_encode(array('error' => $this->l('Shipment is already booked.'))));
        }

        if ($parcel->delete()) {
            // When deleting a package, existing offers are not anymore valid.
            $shipment->invalidateOffer();

            die(json_encode(array('result' => $this->l('Parcel deleted.'))));
        }
    }
}
