# Module for Magento 2 autocomplete for Israel streets and cities

To show house number in admin panel, go to the 

  ```
  Stores -> Configuration -> Customers -> Customer Configuration -> Address Templates
  ```

and add the folofing text to the **Address Templates** fields

  ```
{{depend house_number}}{{var house_number}}{{/depend}}
  ```
  
like on the screenshot, depends on your prepference


![Update street address template](https://i.imgur.com/FCCFw6D.png "Update street address template")