Magento 2 Add Extra Address Fields to Checkout

- It renames extra address attribute fieldname, datascope to customAttributes.{attribute_code} LayoutProcessor
- With a javascript mixin it transports them from customAttributes to extension_attributes
- Plugin
- In a event is transport the values from object to object. Example Quote Address to Order Address

Usage:

- Add a fieldset.xml
- Add a extensions_attributes.xml
- Add a customer_address attribute setup
- Add a quote_address field setup
- Add a order_address field setup

Example: See the module ExtraCheckoutAddressFieldsTest