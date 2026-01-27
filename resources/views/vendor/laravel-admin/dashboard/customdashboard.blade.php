<style>
    .block {
        display: flex;
        height: 150px;
        align-items: center;
        text-align: center;
        border-radius: 20px;
        margin-bottom: 10px;
        box-shadow: rgba(0, 0, 0, 0.35) 5px 5px 15px;

    }
    .block a{
        margin: auto;
        color: #636b6f;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: .1rem;
        text-decoration: none;
        text-transform: uppercase;
    }
</style>

<script src="https://kit.fontawesome.com/89a23f10b5.js" crossorigin="anonymous"></script>

<div class="container-fluid">
    <div class="row">
        <div class="col-xs-6 col-md-3 ml-md-auto upper_block">
            <div class=" bg-success block "><a href="{{url("admin/dashboard/online")}}">
                <i class="fa-solid fa-desktop"></i> Online ({{ $onlinePlayer }}) </a>
            </div>
        </div>
        <div class="col-xs-6 col-md-3 ml-md-auto upper_block ">
            <div class=" bg-danger block"><a href="{{url("admin/dashboard/debt")}}">
                <i class="fa-solid fa-file-invoice-dollar"></i> Debt ({{ $debtCount }}) </a>
            </div>
        </div>
        <div class="col-xs-6 col-md-3 ml-md-auto upper_block ">
            <div class=" bg-info block"><a href="{{url("admin/dashboard/stock")}}">
                <i class="fa-solid fa-boxes-stacked"></i> Instock ({{ $instockCount }}) </a>
            </div>
        </div>
        <div class="col-xs-6 col-md-3 ml-md-auto upper_block ">
            <div class=" bg-warning block"><a href="{{url("admin/dashboard/unpaid")}}">
                <i class="fa-regular fa-money-bill-1"></i> Unpaid ({{ $unpaidCount }})</a>
            </div>
        </div>
    </div>
</div>
