<script>
var import_air = document.getElementById("import_air").value;
var import_sea = document.getElementById("import_sea").value;
var import_arrive = document.getElementById("test1").value;
var test2 = document.getElementById("test2").value;
var on_progress = document.getElementById("count1").value;
var ctx = document.getElementById("myChart");
var data = {
    labels: [
        "Sea",
        "Air",
        "Arrive JKT",
        "On Progress",
        "On Vessel"
    ],
    datasets: [
        {
            data: [import_air, import_sea , import_arrive,test2,on_progress],
            backgroundColor: [
                "#FF6384",
                "#36A2EB",
                "#5858FA",
                "#FE2E9A",
                "#F7FE2E"
            ],
            hoverBackgroundColor: [
                "#FF6384",
                "#36A2EB",
                "#5858FA",
                "#FE2E9A",
                "#F7FE2E"
            ]
        }]
};
var myChart = new Chart(ctx, {
    type: 'pie',
    data: data,
    options: {
        title: {
            display: true,
            text: 'IMPORT SHIPMENT REPORT'
        },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
});
</script>
<script type="text/javascript">
$(document).ready(function() {
    $('#pica').DataTable();
} );
</script>
    <script type="text/javascript">
    $(document).ready(function() {
    $('#import').DataTable({
        responsive: {
            details: {
                display: $.fn.dataTable.Responsive.display.modal( {
                    header: function ( row ) {
                        var data = row.data();
                        return 'Details for '+data[0];
                    }
                } ),
                renderer: $.fn.dataTable.Responsive.renderer.tableAll( {
                    tableClass: 'table'
                } )
            }
        },
        dom: 'lBfrtip',
         "lengthMenu": [ 100, 25, 50, 75 ],
         "order": [[ 9, "asc" ]],
        buttons: [
            //'copyFlash',
            'csvFlash',
            'excelFlash',
            //'pdfFlash',
            //'print',
            'colvis'
        ]
    });
        $('#iv_fwd').DataTable({
        "paging":   false,
        "ordering": true,
        "searching":false,
        "info":     false
    });
        $('#test').DataTable({
        "lengthMenu": [ 100, 25, 50, 75 ],
        dom: 'lBfrtip',
        "lengthMenu": [ 100, 25, 50, 75 ,10000],
        buttons: [
            'csvFlash',
            'excelFlash',
            'colvis'
        ],
        processing: true,
        serverSide: true,
        ajax: '{{asset("test")}}'
    });
});
    $('#done').DataTable({
        responsive: {
            details: {
                display: $.fn.dataTable.Responsive.display.modal( {
                    header: function ( row ) {
                        var data = row.data();
                        return 'Details for '+data[0];
                    }
                } ),
                renderer: $.fn.dataTable.Responsive.renderer.tableAll( {
                    tableClass: 'table'
                } )
            }
        },
        dom: 'lBfrtip',
        "lengthMenu": [ 100, 25, 50, 75 ],
        "order": [[ 13, "desc" ]],
        buttons: [
            'csvFlash',
            'excelFlash',
            'colvis'
        ],
        processing: true,
        serverSide: true,
        ajax: '{{asset("data")}}'
    });
    $('#done_iv').DataTable({
        responsive: {
            details: {
                display: $.fn.dataTable.Responsive.display.modal( {
                    header: function ( row ) {
                        var data = row.data();
                        return 'Details for '+data[0];
                    }
                } ),
                renderer: $.fn.dataTable.Responsive.renderer.tableAll( {
                    tableClass: 'table'
                } )
            }
        },
        dom: 'lBfrtip',
        "lengthMenu": [ 100, 25, 50, 75 ],
        "order": [[ 13, "desc" ]],
        buttons: [
            'csvFlash',
            'excelFlash',
            'colvis'
        ],
        processing: true,
        serverSide: true,
        ajax: '{{asset("data2")}}'
    });
        $('#done_iv_no').DataTable({
        responsive: {
            details: {
                display: $.fn.dataTable.Responsive.display.modal( {
                    header: function ( row ) {
                        var data = row.data();
                        return 'Details for '+data[0];
                    }
                } ),
                renderer: $.fn.dataTable.Responsive.renderer.tableAll( {
                    tableClass: 'table'
                } )
            }
        },
        dom: 'lBfrtip',
        "lengthMenu": [ 100, 25, 50, 75 ],
        "order": [[ 13, "desc" ]],
        buttons: [
            'csvFlash',
            'excelFlash',
            'colvis'
        ],
        processing: true,
        serverSide: true,
        ajax: '{{asset("data3")}}'
    });
    </script>
    <script type="text/javascript">
    function confirm_delete() {
  return confirm('are you sure?');
}
  </script>
    <script type="text/javascript">
    $(function() {

  // We can attach the `fileselect` event to all file inputs on the page
  $(document).on('change', ':file', function() {
    var input = $(this),
        numFiles = input.get(0).files ? input.get(0).files.length : 1,
        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
    input.trigger('fileselect', [numFiles, label]);
  });

  // We can watch for our custom `fileselect` event like this
  $(document).ready( function() {
      $(':file').on('fileselect', function(event, numFiles, label) {

          var input = $(this).parents('.input-group').find(':text'),    
              log = numFiles > 1 ? numFiles + ' files selected' : label;

          if( input.length ) {
              input.val(log);
          } else {
              if( log ) alert(log);
          }

      });
  });
  
});
    </script>
    <script type="text/javascript">
function notifyMe(msg_title, msg_body, redirect_onclick) {
var granted = 0;
// Let's check if the browser supports notifications
if (!("Notification" in window)) {
alert("This browser does not support desktop notification");
}
// Let's check if the user is okay to get some notification
else if (Notification.permission === "granted") {
granted = 1;
}
// Otherwise, we need to ask the user for permission
else if (Notification.permission !== 'denied') {
Notification.requestPermission(function (permission) {
// If the user is okay, let's create a notification
if (permission === "granted") {
granted = 1;
}
});
}
if (granted == 1) {
var notification = new Notification(msg_title, {
body: msg_body,
icon: 'img/icon1.png'
});

if (redirect_onclick) {
notification.onload = function() {
window.location.href = redirect_onclick;
}
}
}
}
</script>
