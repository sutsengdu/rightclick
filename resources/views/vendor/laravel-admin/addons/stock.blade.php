
<style>
    .bigger-grid{
        padding: 25px;
    }
    .grid{
        background-color: white;
        box-shadow: rgba(0, 0, 0, 0.35) 5px 5px 15px;
        border-radius: 20px
    }
    .upper-sample div{
        margin-top: 25px;
        height:10px;
        width: 10px;
        align-items: center;
        text-align: center;
    }

    .upper-sample p{
        margin: 0 auto;
        margin-bottom: 25px;
        font-size: 16px;
        animation: flicker 3s infinite;
        color: #000000;
    }
    @keyframes flicker {
    0% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
    100% {
        opacity: 1;
    }
    }
    .sample1{
        background-color: #8be04e;
    }
    .sample2{
        background-color: #dd8800;
    }
    .sample3{
        background-color: #ff3333;
    }

    .main-block{
        padding:10px;
    }
    .upper-block{
        padding: 10px;
        background-color: white;
        box-shadow: rgba(0, 0, 0, 0.35) 5px 5px 15px;
        border-radius: 20px;
    }
    .block {
        display: flex;
        height: 100px;
        align-items: center;
        text-align: center;
    }
    .block p{
        margin: 0 auto;
    }
    .bg-success {
        background: #8be04e;
    }
    .bg-warning {
        background: linear-gradient(transparent 40%, #dd8800 60%);
    }
    .bg-danger {
        background: linear-gradient(transparent 80%, #ff3333 20%);
    }
</style>
<center>
    <div class="container-fluid bigger-grid">
        <div class="row grid">
            <div class="col-xs-4 upper-sample">
                <div class="sample1"></div>
                <p>More than 10</p>
            </div>
            <div class="col-xs-4 upper-sample">
                <div class="sample2"></div>
                <p>Equal or lower than 10</p>
            </div>
            <div class="col-xs-4 upper-sample">
                <div class="sample3"></div>
                <p>0</p>
            </div>
        </div>
    </div>

<div class="container-fluid">
    <div class="row">
        @foreach ($inventories as $inventory)
            @php
            $formattedQty = intval($inventory->qty);
            @endphp
            <div class="col-xs-6 col-md-2 col-lg-2 main-block">
                <div class="upper-block">
                    <img src="{{url('/images/box.png')}}" class="img img-fluid {{ $inventory->qty > 10 ? 'bg-success' : ($inventory->qty > 0 ? 'bg-warning' : 'bg-danger') }} block"  />
                    <p>{{ $inventory->item_name }}({{ $formattedQty }})</p>
                </div>
            </div>
        @endforeach
    </div>
</div>
</center>
