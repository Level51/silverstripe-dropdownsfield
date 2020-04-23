<% if $isReadonly %>
    <p id="$ID" tabIndex="0" class="form-control-static readonly<% if $extraClass %> $extraClass<% end_if %>" <% include SilverStripe/Forms/AriaAttributes %>>
        $Value
    </p>
<% else %>
    <div class="level51-dropdownsfield"
         id="$ID"
         data-payload='$Payload.RAW'>
        <dropdowns-field />
    </div>
<% end_if %>
