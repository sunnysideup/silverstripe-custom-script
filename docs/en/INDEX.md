In `Page.ss`

```html
<head>
  <% loop HeaderScripts %>
    $Script.Raw
  <% end_loop %>
</head>
<body>
...

<% loop FooterScripts %>
  $Script.Raw
<% end_loop %>
</body
```
