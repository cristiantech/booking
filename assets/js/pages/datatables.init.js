$(document).ready(function () {
    $("#datatable").DataTable();
    
    $('#example').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            //'excelHtml5'
            {
                extend: 'excel',
                title: 'Producción Diaria'
            }
        ]
    } );
    
    var a = $("#datatable-buttons").DataTable({
        lengthChange: !1,
        buttons: [
        {
            extend: 'pdf',
            orientation: 'landscape',
            pageSize: 'A3',
            title: 'Uroanálisis - Hoja de Trabajo',
            exportOptions: {
                modifier: {
                    page: 'current'
                }
            }
        }
    ]
    });
    $("#key-table").DataTable({
        keys: !0
    }), $("#responsive-datatable").DataTable(), $("#selection-datatable").DataTable({
        select: {
            style: "multi"
        }
    }), a.buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)")
});