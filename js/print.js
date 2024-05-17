function printBill(id) {
  // Send AJAX request to the bill printing route
  fetch('print/bill/' + id, {
    method: 'GET',
  })
    .then(response => response.json())
    .then(data => {
      // Open a new window and write the received HTML content
      let printWindow = window.open('');
      printWindow.document.write(data.html);
      printWindow.document.close();

      // Wait for the new window to load before printing
      printWindow.onload = function () {
        printWindow.print();
        printWindow.close();
      };
    })
    .catch(error => console.error('Error:', error));
}

function printKOT(id) {
  // Send AJAX request to the KOT printing route
  fetch('print/kot/' + id, {
    method: 'GET',
  })
  .then(response => response.json())
  .then(data => {
    // Open a new window and write the received HTML content
    let printWindow = window.open('');
    printWindow.document.write(data.html);
    printWindow.document.close();

    // Wait for the new window to load before printing
    printWindow.onload = function () {
      printWindow.print();
      printWindow.close();
    };
  })
    .catch(error => console.error('Error:', error));
}

function printSticker(id) {
  // Send AJAX request to the sticker printing route
  fetch('print/stickers/' + id, {
    method: 'GET',
  })
  .then(response => response.json())
  .then(data => {
    // Open a new window and write the received HTML content
    let printWindow = window.open('');
    printWindow.document.write(data.html);
    printWindow.document.close();

    // Wait for the new window to load before printing
    printWindow.onload = function () {
      printWindow.print();
      printWindow.close();
    };
  })
    .catch(error => console.error('Error:', error));
}
