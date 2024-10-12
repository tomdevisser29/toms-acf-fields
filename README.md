# Tom's ACF Fields

This plug-in adds some extra ACF Field Types that are not available in the base plug-in.

## Website URL

This field will automatically strip the web protocol entered (e.g. "http") and set it to "https" when you save the post, so it's always using a secure protocol.

It's also styled in a way that shows the "https://" prepend before the input.

<img width="583" alt="website-url-field" src="https://github.com/user-attachments/assets/11097969-5d62-474e-b14d-783f8651ed85">

## Phone Number

This field will automatically strip the entered phone number down to only digits. It will then create formatted phone numbers for display, links (compatible with `tel:`), and save the country code, country prefix and originally entered value.

```
array(6) {
  ["country"]=>
  string(2) "nl"
  ["number"]=>
  string(9) "612345678"
  ["display"]=>
  string(15) "+31 6 1234 5678"
  ["stripped"]=>
  string(9) "612345678"
  ["prefix"]=>
  string(3) "+31"
  ["tel"]=>
  string(12) "+31612345678"
}
```

### Adding countries

To add more countries, you can extend this field type in 4 steps.

1. Add the option to `render_field()`
2. Add the display formatting to `format_phone_number_for_display()`
3. Add the validation to `validate_value()`
4. Add the correct prefix to `get_country_prefix()`
