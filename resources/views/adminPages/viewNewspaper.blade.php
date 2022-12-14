@extends('layouts.app')

@section('content')
    @include('layouts.adminMenu')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

    <script type="text/javascript">
        @if (isset($alertTitle))
            Swal.fire(
                '{{ $alertTitle }}',
                '{{ $alertDescription }}',
                '{{ $alertIcon }}'
            )
        @endif
    </script>


    <style>
        #fp-canvas-container {
            height: 65vh;
            width: calc(100%);
            position: relative;
        }

        .fp-img,
        .fp-canvas,
        .fp-canvas-2 {
            position: absolute;
            width: calc(100%);
            height: calc(100%);
            top: 0;
            left: 0;
            z-index: 1;
        }

        #fp-map {
            position: absolute;
            width: calc(100%);
            height: calc(100%);
            top: 0;
            left: 0;
            z-index: 1;
        }

        .fp-canvas {
            z-index: 2;
            background: #6f00000f;
            cursor: crosshair;
        }

        #fp-map {
            z-index: 2;
        }

        area {
            position: absolute;
        }

        area:hover {
            background: #6501010f;
            color: turquoise;
        }

        #save,
        #cancel {
            display: none;
        }

        .fw-bolder:hover {
            border: 2px solid #f90000;

        }
    </style>

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        @if (count($allpages) > 0)
                            <h1 class="m-0">{{ $paperName }}({{ $date }})</h1>
                        @endif

                        @if (count($allpages) == 0)
                            <h1 class="m-0">No Data Available</h1>
                        @endif
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>


        @if (count($allpages) > 0)
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="sticky-top mb-3">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h4 class="card-title">Tools</h4>
                                    </div>
                                    <div class="card-body">
                                        <!-- the events -->
                                        <div id="external-events">

                                            <div class="external-event bg-info" style="cursor: default;">
                                                <input type="hidden" value={{ $paperId }} id="paperId" />
                                                <input type="hidden" value={{ $paperCode }} id="paperCode" />
                                                <input type="hidden" value={{ $pageNo }} id="pageNo" />


                                                <select class="form-control select2"
                                                    style="background:#17a2b8!important;border: none;color: white;"id="paperEdition"
                                                    name="paperEdition" style="width: 100%;">
                                                </select>

                                            </div>
                                            <div class="external-event bg-info" style="cursor: default;"><input
                                                    type="date" value="{{ date('Y-m-d', strtotime($date)) }}"
                                                    style="background:#17a2b8!important;border: none;color: white;"
                                                    id="defaultDate" /></div>


                                            @if (count($allpages) > 0)
                                                @foreach ($allpages as $page)
                                                    <div class="external-event bg-grey" id="pages"
                                                        style="cursor: default;">
                                                        <a
                                                            href="/viewNewsPaper/{{ $paperCode }}/{{ $page->edition }}/{{ $page->date }}/{{ $page->pageNo }}">{{ $page->pageTitle }}</a>
                                                    </div>
                                                @endforeach
                                            @endif


                                            <br>
                                            <div class="external-event bg-success" style="cursor: default;" id="map_area">
                                                Add
                                                Area</div>

                                            {{-- <div class="external-event bg-red" style="cursor: default; display:none;"
                                                id="saveArea">
                                                Save
                                                Area</div> --}}

                                            <button class="external-event bg-info" type="button" id="save">Save
                                                Mapped Area</button>
                                            <button class="external-event bg-red" type="button"
                                                id="cancel">Cancel</button>




                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->

                            </div>
                        </div>
                        <div class="col-md-9">


                            <div class="card card-primary">
                                <div class="card-header">
                                    @if (count($allpages) > 0)
                                        <h3 class="card-title">{{ $paperName }} - {{ $page->pageTitle }}</h3>
                                    @endif


                                </div>
                                <div class="row" style="margin:0">
                                    <div class="col-12" id="fp-canvas-container" style="width:100%; height:100%;">
                                        <map name="fp-map" id="fp-map">
                                        </map>
                                        <img src="{{ asset(urldecode($frontPage->pageImageUrl)) }}" id="fp-img"
                                            width="100%" usemap="#fp-map"></img>
                                        <canvas class="fp-canvas d-none" id="fp-canvas"></canvas>
                                    </div>
                                </div>



                            </div>
                            <!-- /.card -->
                        </div>

                        <!-- /.row -->
                    </div>
                    <!-- /.container-fluid -->
                </div>
            </section>

        @endif

    </div>
    <!-- /.content-wrapper -->




    <div class="modal fade bd-example-modal-xl" id="view_modal" tabindex="-1" role="dialog"
        aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-body" style="overflow:scroll;">

                </div>
                <div class="modal-footer py-1">
                    <p>Share On: </p>
                    <button type="button" style="background: none;stroke: none;border: none;" onclick="shareFacebook()">
                        <ion-icon name="logo-facebook" style="height:40px;width:40px"></ion-icon>
                    </button>

                    <button type="button" style="background: none;stroke: none;border: none;" onclick="shareFacebook()">
                        <ion-icon name="logo-whatsapp" style="height:40px;width:40px"></ion-icon>
                    </button>


                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                </div>
            </div>
        </div>
    </div>


    <!-- Mapped Area Add/Edit Details Form Modal -->
    <div class="modal fade" id="form_modal" role='dialog' data-bs-backdrop="static" data-bs-keyboard="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Mapped Area Details</h5>
                </div>
                <div class="modal-body">
                    <form action="" id="mapped-form">
                        <input type="hidden" name="form_id" value="">
                        <input type="hidden" name="form_x1" value="">
                        <input type="hidden" name="form_y1" value="">
                        <input type="hidden" name="form_x2" value="">
                        <input type="hidden" name="form_y2" value="">
                        <input type="hidden" name="form_paperId" value="">
                        <input type="hidden" name="form_date" value="">
                        <input type="hidden" name="form_edition" value="">
                        <input type="hidden" name="form_pageNo" value="">
                        <div class="form-group">
                            <label for="form_description" class="control-label text-primary">Description</label>
                            <textarea name="form_description" id="form_description" cols="30" rows="4"
                                class="form-control rounded-0"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer py-1">
                    <button type="submit" class="btn btn-sm rounded-0 btn-primary" form="mapped-form">Save</button>
                    <button type="button" class="btn btn-sm rounded-0 btn-secondary"
                        data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!--End of Mapped Area Add/Edit Details Form Modal -->


    {{-- <script src="{{ asset('adminAsset/misc/maparea.js') }}"></script> --}}


    <link rel="stylesheet" href=" https://cdn.datatables.net/1.11.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.0/css/buttons.dataTables.min.css">

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.0/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.5/js/dataTables.responsive.js"></script>



    <script>
        $(document).ready(function() {
            $('#table21').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                order: [4, 'DESC'],
            });
        });
    </script>

    <script>
        /* jQuery Part */
        $("#defaultDate").change(function() {

            var edition = $("#paperEdition").val();
            var paperCode = $("#paperCode").val();
            selectedDate = $('#defaultDate').datepicker({
                dateFormat: 'yy-mm-dd'
            }).val();
            // alert(selectedDate);
            window.location.href = '/viewNewsPaper/' +
                paperCode + '/' + edition + '/' + selectedDate + '/' + 1; //Will take you to Google.

        });
    </script>





    <script type="text/javascript">
        $(document).ready(function() {
            var paperId = $('#paperId').val();
            $.ajax({
                type: 'POST',
                url: '/api/getEditions/',
                dataType: 'JSON',
                data: {
                    id: paperId,
                },

                success: function(data) {

                    var allData = data.allEditions;
                    var select = document.getElementById("paperEdition");

                    for (var i = 0; i < allData.length; i++) {
                        var opt = allData[i].name;
                        var el = document.createElement("option");
                        el.textContent = opt;
                        el.value = allData[i].id;
                        select.appendChild(el);
                    }
                },
                error: function(jqXHR, textStatus) {
                    alert(textStatus);

                }
            });

        })
    </script>

    <script>
        // console.log({!! $date !!})


        var isdrawing = false;

        var initialX = 0;
        var initialY = 0;

        var tempX = 0;
        var tempY = 0;

        var finalX = 0;
        var finalY = 0;

        var width = 0;
        var height = 0;

        var px1_perc = 0;
        var py1_perc = 0;

        var px2_perc = 0;
        var py2_perc = 0;


        var initialX_perc = 0;
        var initialY_perc = 0;

        function getCursorPosition(canvas, event) {

            const rect = canvas.getBoundingClientRect();
            // var scaleX = canvas.width / rect.width; // relationship bitmap vs. element for x
            // var scaleY = canvas.height / rect.height; // relationship bitmap vs. element for y


            const x = (event.clientX - rect.left) / (rect.right - rect.left) * canvas.width;
            const y = (event.clientY - rect.top) / (rect.bottom - rect.top) * canvas.height;

            // console.log(rect)
            // const x = (event.clientX - rect.left) * scaleX;
            // const y = (event.clientY - rect.top) * scaleY;
            // const x = (event.clientX - rect.left);
            // const y = (event.clientY - rect.top);
            // console.log(x, y)
            return [x - 6, y];

        }


        $(document).ready(function() {
            var myCanvas = document.getElementById("fp-canvas");
            var myCtx = myCanvas.getContext("2d");
            var fp_image = document.getElementById("fp-img");
            myCanvas.width = fp_image.clientWidth;
            myCanvas.height = fp_image.clientHeight;


            // myCtx.clearRect(0, 0, myCanvas.width, myCanvas.height);
            // myCtx.beginPath();
            // myCtx.lineWidth = "3";
            // myCtx.strokeStyle = "red";
            // myCtx.rect(-50, -50, 50, 50);
            // myCtx.stroke();

            // console.log("Width" + myCanvas.width)
            // console.log("Height" + myCanvas.height)

            // console.log(myCanvas.getBoundingClientRect())
        })


        $(function() {
            cposX = $('#fp-canvas')[0].getBoundingClientRect().x
            cposY = $('#fp-canvas')[0].getBoundingClientRect().y
            ctx = $('#fp-canvas')[0].getContext('2d');

            // Creates the Map Area on the Image
            mapped_area()

            // Re-initialize Map Area Creation when the window has been resized
            $(window).on('resize', function() {
                mapped_area()
            })

        });



        // Event Listener when the mouse is clicked on the canvas area
        $('.fp-canvas').on('mousedown', function(e) {

            var myCanvas = document.getElementById("fp-canvas");
            var myCtx = myCanvas.getContext("2d");

            var xy_cord = getCursorPosition(myCanvas, e);
            initialX = xy_cord[0];
            initialY = xy_cord[1];


            initialX_per = (initialX / 100) * myCanvas.width
            initialY_per = (initialY / 100) * myCanvas.height


            isdrawing = true;





            // myCtx.clearRect(0, 0, myCanvas.width, myCanvas.height);
            // myCtx.beginPath();
            // myCtx.lineWidth = "3";
            // myCtx.strokeStyle = "red";
            // myCtx.rect(initialX, initialY, 50, 50);
            // myCtx.stroke();


            // console.log(e.clientX);
            // console.log(e.offsetX);

        })

        // Event Listener when the mouse is moving on the canvas area. For drawing the rectangular Area
        $('.fp-canvas').on('mousemove', function(event) {

            var myCanvas = document.getElementById("fp-canvas");
            var myCtx = myCanvas.getContext("2d");

            if (isdrawing == false) return false;

            var xy_cord = getCursorPosition(myCanvas, event);
            // tempX = event.clientX - myCanvas.getBoundingClientRect().left;
            // tempY = event.clientY - myCanvas.getBoundingClientRect().top;
            tempX = xy_cord[0];
            tempY = xy_cord[1];

            width = tempX - initialX;
            height = tempY - initialY;

            myCtx.clearRect(0, 0, myCanvas.width, myCanvas.height);
            myCtx.beginPath();
            myCtx.lineWidth = "1";
            myCtx.strokeStyle = "red";
            myCtx.rect(initialX, initialY, width, height);
            myCtx.stroke();
        })
        // Event Listener when the mouse is up on the canvas area. End of Drawing
        $('.fp-canvas').on('mouseup', function(event) {
            var myCanvas = document.getElementById("fp-canvas");
            var myCtx = myCanvas.getContext("2d");

            var xy_cord = getCursorPosition(myCanvas, event);
            finalX = xy_cord[0];
            finalY = xy_cord[1];


            width = finalX - initialX;
            height = finalY - initialY;

            finalX_per = (finalX / 100) * myCanvas.width
            finalY_per = (finalY / 100) * myCanvas.height


            // console.log(width, height);

            myCtx.clearRect(0, 0, myCanvas.width, myCanvas.height);
            myCtx.beginPath();
            myCtx.lineWidth = "1";
            myCtx.strokeStyle = "red";
            myCtx.rect(initialX, initialY, width, height);
            myCtx.stroke();


            isdrawing = false;
        })

        // Action when Map Are button is clicked
        $('#map_area').click(function() {
            $(this).hide('slow')
            $('#save,#cancel').show('slow')
            $('#fp-canvas').removeClass('d-none')
            cposX = $('#fp-canvas')[0].getBoundingClientRect().x
            cposY = $('#fp-canvas')[0].getBoundingClientRect().y
        })
        // Action when Map Are cancel is clicked
        $('#cancel').click(function() {
            $('#save,#cancel').hide('slow')
            $('#map_area').show('slow')
            $('#fp-canvas').addClass('d-none')
        })
        // Action when Map Are save is clicked
        $('#save').click(function() {
            // var cP = px1_perc + ", " + py1_perc + ", " + px2_perc + ", " + py2_perc;
            var paperId = String({{ $paperId }});
            var pageNo = $("#pageNo").val();
            var currentDate = $('#defaultDate').val()


            // var day = currentDate.getDate()
            // var month = currentDate.getMonth() + 1
            // var year = currentDate.getFullYear()

            // var date = year + "-" + ("0" + (month)).slice(-2) + "-" + ("0" + (day)).slice(-2)


            console.log(initialX_per, initialY_per, finalX_per, finalY_per)



            var edition = String({{ $edition }});
            $('#form_modal').find('input[name="form_x1"]').val(initialX_per)
            $('#form_modal').find('input[name="form_y1"]').val(initialY_per)
            $('#form_modal').find('input[name="form_x2"]').val(finalX_per)
            $('#form_modal').find('input[name="form_y2"]').val(finalY_per)
            $('#form_modal').find('input[name="form_paperId"]').val(paperId)
            $('#form_modal').find('input[name="form_pageNo"]').val(pageNo)
            $('#form_modal').find('input[name="form_date"]').val(currentDate)
            $('#form_modal').find('input[name="form_edition"]').val(edition)
            $('#form_modal').modal('show')
        })


        // Saving the Mapped Area Details on local Storage
        $('#mapped-form').submit(function(e) {
            e.preventDefault();
            var data;
            var id = $(this).find('[name="form_id"]').val()
            var x1 = $(this).find('[name="form_x1"]').val()
            var y1 = $(this).find('[name="form_y1"]').val()
            var x2 = $(this).find('[name="form_x2"]').val()
            var y2 = $(this).find('[name="form_y2"]').val()
            var description = $(this).find('[name="form_description"]').val()
            var date = $('#defaultDate').val()
            var paperId = $('#paperId').val()
            var pageNo = $("#pageNo").val();
            var edition = $(this).find('[name="form_edition"]').val()

            console.log(x1, y1, x2, y2)




            $.ajax({
                type: 'POST',
                url: '/api/saveMappedArea',
                dataType: 'JSON',
                data: {
                    _token: <?php echo "'" . csrf_token() . "'"; ?>,
                    id: id,
                    x1: x1,
                    y1: y1,
                    x2: x2,
                    y2: y2,
                    description: description,
                    date: date,
                    paperId: paperId,
                    pageNo: pageNo,
                    edition: edition,
                },

                success: function(data) {
                    Swal.fire(
                        'Area Mapped Successfully',
                        '',
                        'success'
                    ).then(() => {
                        location.reload();
                    });

                },
                error: function(jqXHR, textStatus) {
                    console.log(jqXHR)
                }
            });
        })




        function mapped_area() {
            var myCanvas = document.getElementById("fp-canvas");
            var myCtx = myCanvas.getContext("2d");
            var fp_image = document.getElementById("fp-img");
            myCanvas.width = fp_image.clientWidth;
            myCanvas.height = fp_image.clientHeight;

            @if (count($mapAreaData) != 0)
                mapAreaData = {!! $mapAreaData !!};
            @endif

            console.log(mapAreaData);
            Object.keys(mapAreaData).map(k => {

                var data = mapAreaData[k];
                var area = $("<area shape='rect'>");
                area.attr("href", "javascript:void(0)");
                var new_x1 = (data.x1 * 100) / myCanvas.width;
                var new_y1 = (data.y1 * 100) / myCanvas.height;

                var new_x2 = (data.x2 * 100) / myCanvas.width;
                var new_y2 = (data.y2 * 100) / myCanvas.height;

                var width = new_x2 - new_x1;
                var height = new_y2 - new_y1;




                // var y = $('#fp-img').height() * perc[1];
                // var width = Math.abs(($('#fp-img').width() * Math.abs(perc[2])) - x);
                // var height = Math.abs(($('#fp-img').height() * Math.abs(perc[3])) - y);



                // if ($("#fp-img").width() * data.width - x < 0) x = x - width;
                // if ($("#fp-img").height() * data.height - y < 0) y = y - height;
                // console.log("X+1", x + ", " + y + ", " + width + ", " + height);

                area.attr("coords", new_x1 + ", " + new_y1 + ", " + width + ", " + height);
                area.addClass(
                    "fw-bolder ");
                area.css({
                    height: height + "px",
                    width: width + "px",
                    top: new_y1 + "px",
                    left: new_x1 + "px",
                });

                $("#fp-map").append(area);


                area.click(function() {
                    var ctx = $("#fp-canvas")[0].getContext("2d");
                    // console.log("X+2", x + ", " + y + ", " + width + ", " + height);

                    var ImageData = ctx.getImageData(new_x1, new_y1, width, height);
                    // create image element
                    var MyImage = new Image();
                    var canvas = $('#fp-canvas')[0];
                    // var ctx = $("#fp-canvas").getContext("2d");
                    canvas.width = width;
                    canvas.height = height;
                    ctx.putImageData(ImageData, 0, 0);
                    // = canvas.toDataURL();

                    var image = document.getElementById("fp-img");

                    ctx.drawImage(image,
                        new_x1, new_y1, //from where
                        width, height, //how big it would be of the snapshot
                        0, 0, //where we want to be placed
                        width, height //size of the placement
                    );
                    MyImage.src = canvas.toDataURL();
                    MyImage.id = "imageToshare";
                    MyImage.alt = "Find these article on Amar Asom.";
                    $('.modal-body').html(MyImage);
                    $('#view_modal').modal('show');
                })
            });


        }
    </script>

    <script>
        function shareFacebook() {
            alert('Facebook')

            // u = TheImg.src;
            u = $("#imageToshare").src;

            // t=document.title;
            t = $("#imageToshare").alt;
            window.open('http://www.facebook.com/sharer.php?u=' + encodeURIComponent(u) + '&t=' + encodeURIComponent(t),
                'sharer', 'toolbar=0,status=0');
            return false;

        }
    </script>
@endsection
