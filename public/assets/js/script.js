$(document).ready(function() {
    $('#terme').on('keyup', function() {
      var terme = $(this).val();
  
      $.ajax({
        // ... existing AJAX code ...
        success: function(data) {
            var container = $('#donations-container');
            container.html(''); // Clear existing content
          
            if (data.length > 0) {
              // Build the table content dynamically (optional)
              // ... (you can use a loop to build the table rows) ...
              // Or append the received data directly (if formatted correctly)
              container.append(data);
            } else {
              container.append('<tr><td colspan="4" class="text-center">No records found</td></tr>');
            }
          }
      });
    });
  });