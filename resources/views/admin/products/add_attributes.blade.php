@extends('layouts.adminLayout.admin_design')
@section('content')
<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i>Home</a>
      <a href="#"> Products Attributes</a> <a href="#" class="current">Add Product Attributes</a> </div>
    <h1>Products Attributes</h1>
    @if (Session::has('flash_message_error'))
      <div class="alert alert-danger alert-block">
          <button type="button" class="close" data-dismiss="alert">×</button>
          <strong>{{ Session('flash_message_error') }}</strong>
      </div>
    @endif
    @if (Session::has('flash_message_success'))
      <div class="alert alert-success alert-block">
          <button type="button" class="close" data-dismiss="alert">×</button>
          <strong>{{ Session('flash_message_success') }}</strong>
      </div>
    @endif
  </div>
  <div class="container-fluid"><hr>
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
            <h5>Add Products Attributes</h5>
          </div>
          <div class="widget-content nopadding">

            <form class="form-horizontal" method="post" action="{{ url('/admin/add-attributes/'.$productDetails->id)}}"
            name="add_attribute" id="add_attribute"  enctype="multipart/form-data">
              {{ csrf_field() }}
              <input type="hidden" name="product_id" value="{{ $productDetails->id }}"><!-- product id -->
              <div class="control-group">
                <label class="control-label">Product Name</label>
                <label class="control-label"><strong> {{ $productDetails->product_name }} </strong></label>
              </div>
              <div class="control-group">
                <label class="control-label">Product Code</label>
                <label class="control-label"><strong> {{ $productDetails->product_code }} </strong></label>
              </div>
              <div class="control-group">
                <label class="control-label">Product Color</label>
                <label class="control-label"><strong> {{ $productDetails->product_color }} </strong></label>
              </div>
              <div class="control-group">
                <label class="control-label"></label>
                  <div class="field_wrapper">
                    <div>
                      <input required type="text" name="sku[]" id="sku"  placeholder="SKU"style="width:120px"/>
                      <input required type="text" name="size[]" id="size"  placeholder="Size"style="width:120px"/>
                      <input required type="text" name="price[]" id="price"  placeholder="Price"style="width:120px"/>
                      <input required type="text" name="stock[]" id="stock"  placeholder="Stock"style="width:120px"/>
                      <a href="javascript:void(0);" class="add_button btn btn-success" title="Add field">Add</a>
                    </div>
                  </div>
              </div>

              <div class="form-actions">
                <input type="submit" value="Add Attributes" class="btn btn-success">
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>

    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>View Attributes</h5>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table">
              <thead>
                <tr>
                  <th>Attribute ID</th>
                  <th>Attribute Sku</th>
                  <th>Attribute Size</th>
                  <th>Attribute Price</th>
                  <th>Attribute Stock</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @php $i=1; @endphp
            @foreach ($productDetails['attributes'] as $attribute)

                <tr class="gradeX">
                  <td>{{ $i++}}</td>
                  {{-- <td>{{ $product->id }}</td> --}}
                  <td> {{ $attribute->sku}} </td>
                  <td> {{ $attribute->size}} </td>
                  <td> {{ $attribute->price}} </td>
                  <td> {{ $attribute->stock }}</td>
                  <td class="center">
                    <a rel="{{ $attribute->id }}" rel1="delete-attribute"
                      href="javascript:"
                      class="btn btn-danger btn-mini deleteRecord">Delete</a>
                  </td>
                </tr>
              @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection
