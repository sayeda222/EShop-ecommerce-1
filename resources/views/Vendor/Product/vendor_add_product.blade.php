@extends('Vendor.vendor_master')
@section('vendor-home')
    <div id="content-wrapper">
        <div class="container-fluid">
            <!-- DataTables Example -->
            <div class="card mb-3">
                <div class="card-header">
                    <i class="fas fa-table"></i>
                    Add a New Product To Your Inventory
                </div>
                <h3 style="color:red" align="center">{{Session::get('message')}}</h3>
                <div class="card-body">
                    <form class="form-group" action="{{route('vendor-save-product')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-row">
                            <div class="col">
                                <label for="product_name">Product Name</label>
                                <input required type="text" class="form-control" name="product_name" id="product_name"/>
                                <input type="hidden" class="form-control" name="vendor_id" value="{{Session::get('vendorid')}}" />
                                <small class="text-danger"></small>
                            </div>
                            <div class="col">
                                <label for="product_description">Description</label>
                                <textarea required type="text" class="form-control" name="product_description" id="product_description"></textarea>
                                <small class="text-danger"></small>
                            </div>
                            <div class="col">
                                <label for="product_description">Specification</label>
                                <textarea required type="text" class="form-control" name="product_specification" id="product_description"></textarea>
                                <small class="text-danger"></small>
                            </div>
                        </div>

                        <br>
                        <hr>
                        <br>

                        <div class="form-row">
                            <div class="col">
                                <label for="pc">Category</label>
                                <select required class="form-control form-control-sm" id="pc" name="category_id" onchange="getCategories(this)">
                                    <option value="0">Select</option>
                                    @foreach($productCategory as $v_category)
                                        <option value="{{$v_category->id}}">{{$v_category->category_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col" id="cat_level_one" style="display:none">
                                <label for="psc">Sub-Category 1</label>
                                <select  class="form-control form-control-sm" id="psc1" name="cat_level_one" onchange="catLevelTwo(this)">

                                    <option value="">Select</option>

                                </select>
                            </div>
                            <div class="col" id="cat_level_two" style="display:none">
                                <label for="psc">Sub-Category 2</label>
                                <select  class="form-control form-control-sm" id="psc2" name="cat_level_two" onchange="catLevelThree(this)">
                                    <option value="">Select</option>

                                </select>
                            </div>
                            <div class="col" id="cat_level_three" style="display:none">
                                <label for="psc">Sub-Category 3</label>
                                <select  class="form-control form-control-sm" id="psc3" name="cat_level_three" onchange="catLevelFour(this)">
                                    <option value="">Select</option>

                                </select>
                            </div>
                            <div class="col">
                                <label for="pb">Brand</label>
                                <select required class="form-control form-control-sm" id="pb" name="brand_id">

                                    <option value="">Select</option>
                                    @foreach($vendor_brand as $v_brands)
                                        <option value="{{$v_brands->id}}">{{$v_brands->brand_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col">
                                <label for="pb">Type</label>
                                <select required class="form-control form-control-sm" id="pb" name="type_id">

                                    <option value="">Select</option>
                                    @foreach($types as $v_type)
                                        <option value="{{$v_type->id}}">{{$v_type->type_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <br>

                        <div class="form-row">
                            <div class="col-md-3">
                                <label for="pr">Price</label>
                                <input required type="text" class="form-control form-control-sm" id="pr" name="product_price" />
                                <small class="text-danger"></small>
                            </div>
                            <div class="col-md-3">
                                <label for="cur">Currency</label>
                                <!-- <select class="form-control form-control-sm" id="cur">
                                    <option>Small select</option>
                                </select> -->
                                <input required type="text" class="form-control form-control-sm" value="BDT" id="cur" name="currency" />

                                <small class="text-danger">e:g: BDT, USD, EUR </small>
                            </div>
                            <div class="col-md-2">
                                <label for="unit">Unit</label>
                                <!-- <select class="form-control form-control-sm" id="unit">
                                    <option>Small select</option>
                                </select> -->
                                <input required type="text" class="form-control form-control-sm" id="unit" name="sell_unit" />
                                <small class="text-danger">Display Price is cost per unit. e.g: Kg, g, Piece </small>
                                <small class="text-danger"></small>
                            </div>
                            <div class="col-md-2">
                                <label for="qt">Quantity</label>
                                <input type="number" class="form-control form-control-sm" id="qt" name="product_quantity" />
                                <small class="text-danger">Don't add quantity if the product is of **Clothing Type</small>
                            </div>
                            <div class="col-md-2">
                                <label for="discount">Discount</label>
                                <input type="number" class="form-control form-control-sm" id="discount" name="discount" />
                            </div>
                        </div>

                        <br>
                        <hr>
                        <br>


                        <small class="text-info">**Please set a proper suitable name for the image file before uploading.</small>
                        <div class="form-row">
                            <div class="col">
                                <label for="file-upload" class="btn btn-primary btn-lg btn-icon-split" style="cursor: pointer">
                            <span class="icon text-white-50">
                                <i class="fas fa-file-upload"></i>
                            </span>
                                    <span class="text text-white-50">Upload Image</span>
                                </label>
                                <input required id="file-upload"  style="display: none" type="file" name="product_image" />
                                <h4 style="color:red">{{$errors->has('product_image')?$errors->first('product_image'):''}}</h4>
                            </div>

                            <div class="col">
                                <div class="">
                                    <div class="">
                                        <img id="blah" src="#" alt="" style="height:100px; border: 2px solid grey" />
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!--   <div class="form-row">
                              <div class="col-md-4">
                                  <label for="sz">Size</label>
                                  <input type="text" class="form-control form-control-sm" id="sz" name="product_size" />
                              </div>
                              <div class="col-md-4">
                                  <label for="cl">Color</label>
                                  <input type="text" class="form-control form-control-sm" id="cl" name="product_color" />
                              </div>
                              <div class="col-md-3">
                                  <label for="sub">Click to Submit</label>
                                  <input type="submit" value="Submit" class="form-control form-control-sm btn btn-primary btn-sm btn-block" id="sub" name="submit" />
                              </div>
                          </div> -->
                        <div class="form-row">
                            <div class="col-md-12">
                                <div class="text-center pt-5">
                                    <input type="submit" class="btn btn-success btn-lg">
                                    <br>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <!-- /.content-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <div class="modal fade" id="productDetailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Product Detail</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="product-detail">

                    <h2 id="product-name"></h2>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @include('Vendor.Endgame.endgame')
    <script>



        $('#blah').hide();


        function readURL(input) {
            if (input.files && input.files[0]) {

                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#blah').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }

            $('#blah').show();
        }

        $("#file-upload").change(function() {
            readURL(this);
        });





        // function for get brands according to vendor
        function getBrands(vendor_id){
            var url = ""+vendor_id;
            if(vendor_id){
                $.ajax({
                    type : 'POST',
                    url : url,
                    dataType : 'json',
                    success : function(brands){
                        console.log(brands);
                        var brand_count = brands.length;

                        var html = `<option value="">select</option>`;
                        for(var i = 0; i < brand_count; i++){
                            html += `
                            <option value="${brands[i].id}">${brands[i].brand_name}</option>
                        `;
                        }
                        $('#pb').html(html);
                    }
                })
            }
        }

        // get child category level 1
        function getCategories(v){


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var cat_id=$(v).val();
            var url = "./get-categories/" + cat_id;
            if(cat_id){
                $.ajax({
                    type : 'GET',
                    url : url,
                    data:{cat_id:cat_id},
                    dataType : 'json',
                    success : function(categories){
                        var category_count = categories.cat_data.length;

                        var html = `<option value="">select</option>`;
                        for(var i = 0; i < category_count; i++){
                            html += `
                            <option value="${categories.cat_data[i].id}">${categories.cat_data[i].category_name}</option>
                        `;
                        }
                        $('#psc1').html(html);
                        $("#cat_level_one").css("display","block");
                    }
                })
            }
        }



        // get child category level 2
        function catLevelTwo(v){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var cat_id=$(v).val();
            var url = "/get-categories/"+cat_id;
            if(cat_id){
                $.ajax({
                    type : 'GET',
                    url : url,
                    data:{cat_id:cat_id},
                    dataType : 'json',
                    success : function(data){
                        var category_count = data.cat_data.length;

                        var html = `<option value="">select</option>`;
                        for(var i = 0; i < category_count; i++){
                            html += `
                            <option value="${data.cat_data[i].id}">${data.cat_data[i].category_name}</option>
                        `;
                        }
                        $('#psc2').html(html);
                        $("#cat_level_two").css("display","block");
                    }
                })
            }
        }

        // get child category level 3
        function catLevelThree(v){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var cat_id=$(v).val();
            var url = "/get-categories/"+cat_id;
            if(cat_id){
                $.ajax({
                    type : 'GET',
                    url : url,
                    dataType : 'json',
                    success : function(data){
                        var category_count = data.cat_data.length;

                        var html = `<option value="">select</option>`;
                        for(var i = 0; i < category_count; i++){
                            html += `
                            <option value="${data.cat_data[i].id}">${data.cat_data[i].category_name}</option>
                        `;
                        }
                        $('#psc3').html(html);
                        $("#cat_level_three").css("display","block");
                    }
                })
            }
        }


    </script>
@endsection
