<style>
    .upper{
        padding: 10px;
    }
    .upper-block{
        background: #ffffff;
        border-radius: 20px;
        box-shadow: rgba(0, 0, 0, 0.35) 5px 5px 15px;
    }
    .block {
        display: flex;
        height: 100px;
        align-items: center;
        text-align: center;
    }
    .pc-text {
        margin: 0 auto;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: .1rem;
        text-decoration: none;
        text-transform: uppercase;
        margin-bottom: 10px;
    }
    .bg-success {
        background: #00ff00;
        color: #040000;
    }
    .bg-danger {
        background: #ff3333;
        color: #ffffff;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <center>
            @foreach($statusArray as $status)
            <div class="col-xs-6 col-md-2 col-lg-2 upper">
                <div class="upper-block">
                <a href="#" data-toggle="modal" data-target="#exampleModal{{$loop->index}}">
                    <img src="{{url('/images/computer.png')}}" class="img img-fluid {{ in_array($status, $seats) ? 'bg-success' : 'bg-danger' }} block"  />
                </a>
                <p class="pc-text">{{ $status }}</p>
                </div>
            </div>
            @endforeach
        </center>
    </div>
</div>


@foreach($statusArray as $index => $status)
<div class="modal fade" id="exampleModal{{$index}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Member ID</h1>
            </div>
            <div class="modal-body">
                @if(in_array($status, $seats) && isset($seatMemberIds[$status]))
                    <p>Member ID : {{ $seatMemberIds[$status] }}</p>
                @else
                    <p>Available</p>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach

