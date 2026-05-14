    <script src="./scripts/script.js"></script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script src="https://flowbite-admin-dashboard.vercel.app/app.bundle.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.2/datepicker.min.js"></script> -->
    <script src="../node_modules/flowbite/dist/flowbite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
    
     <!-- Flowbite Datatables Script -->
    <script>
        document.querySelectorAll(".datatable").forEach((table) => {
            new simpleDatatables.DataTable(table);

             if (table && typeof simpleDatatables.DataTable !== 'undefined') {
                const dataTable = new simpleDatatables.DataTable("#sorting-table", {
                    searchable: false,
                    perPageSelect: false,
                    sortable: true
                });
            }
        });
    </script>
    
  </body>
</html>