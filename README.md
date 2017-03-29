Magento 2 Add Extra Address Fields to Checkout

- It renames extra address attribute fieldname, datascope to customAttributes.{attribute_code} LayoutProcessor
- With a javascript mixin it transports them from customAttributes to extension_attributes
- Plugin
- In a event is transport the values from object to object. Example Quote Address to Order Address

Usage:

1. Add a fieldset.xml 
```
 <scope id="global">
        <fieldset id="extra_checkout_billing_address_fields">
            <field name="attribute_code">
                <aspect name="to_order_address" />
                <aspect name="to_customer_address" />
            </field>
        </fieldset>
 </scope>
```
2. Add a extensions_attributes.xml
2. Add a customer_address attribute setup
2. Add a quote_address field setup
2. Add a order_address field setup

**Example**: See the module [ExtraCheckoutAddressFieldsTest](https://github.com/experius/Magento-2-Module-Experius-ExtraCheckoutAddressFieldsTest)
