@extends('layout.azurelayout')
@section('azure-content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="text-center">Azure Storage</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            @if ($errors->any())
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert">×</button>
                Fill All Fields
            </div>
            @endif</div>
    </div>
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <form action="{{route('azure.store')}}" method="post" enctype="multipart/form-data">
                @csrf

                <label for="">File</label>
                <input type="file" class="form-control" name="file" multiple>
                <label for="">Blob Name</label>
                <?php $returnResult = getListOfContainers(); ?>

                <?php //dump($row->getName());
                ?><select name="blob_name" id="container_name" class="form-control">
                    <option value="">Select Your Container</option>
                    @foreach($returnResult->getContainers() as $row)
                    <option value="{{$row->getName()}}">{{$row->getName() ?? ''}}</option>
                    @endforeach
                </select>

                <div id="newContainer">
                    <label for="">Create New Container</label>
                    <input type="text" name="newContainerInput" id="newContainerInput" class="form-control" placeholder="Enter the Name of Container">
                </div>
                <!-- <input type="text" class="form-control" name="blob_name"> -->
                <input type="button" name="new_container" id="new_container" class="class btn btn-info" value="Create a Container">

                <button type="submit" class="btn btn-info m-3">Submit</button>
            </form>
        </div>
        <div class="col-md-2"></div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $("#newContainer").hide();
        $("#new_container").click(function() {
            $("#newContainer").show();
        });
    });
</script>

@endsection
@section('azure-scripts')
@parent
<script>
    $(document).ready(function() {
        $("#newContainer").hide();
        $("#new_container").click(function() {
            $("#newContainer").toggle();
        });
    });
</script>
@endsection
