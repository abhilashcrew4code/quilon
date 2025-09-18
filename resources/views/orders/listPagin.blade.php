<table class="table">
    <thead>
        <tr>
            <th>Sl No.</th>
            <th>Name</th>
            <th>Amount</th>
            <th>Date</th>
            <th>Payment Status</th>
            <th>Delivery Status</th>
            <th>Manage</th>
        </tr>
    </thead>

    


    <tbody class="table-border-bottom-0">

        @forelse($orders as $key => $value)
        
        <tr>
            <td> {{ $key + $orders->firstItem()}}</td>
            <td>{{ $value->customer_name }}</td>
            <td>{{ $value->total_amount }}</td>
            <td>{{ $value->date }}</td>
            <td>
                @if($value->payment_status == 1)
                    <span class="badge bg-success">Paid</span>
                @else
                    <span class="badge bg-danger">Unpaid</span>
                @endif
            </td>

            <td>
                @switch($value->delivery_status)
                    @case(0)
                        <span class="badge bg-secondary">Pending</span>
                        @break
                    @case(1)
                        <span class="badge bg-info text-dark">Shipped</span>
                        @break
                    @case(2)
                        <span class="badge bg-success">Delivered</span>
                        @break
                    @case(3)
                        <span class="badge bg-danger">Cancelled</span>
                        @break
                    @default
                        <span class="badge bg-dark">Unknown</span>
                @endswitch
            </td>

            <td>

                <a href="#" data-endpoint="{{ route('orders.edit', $value->id) }}" data-async="true"
                    data-toggle="tooltip" data-id="{{ $value->id }}" title="Edit" class="editUser"
                    data-target="modal-xl" data-cache="false" data-act_type="editUser"><i
                        class="mdi mdi-pencil-outline me-2"></i></a>

                <a href="{{ route('orders.print', $value->id) }}" class="btn btn-sm btn-primary" target="_blank">
                    <i class="mdi mdi-printer me-1"></i>
                </a>

                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#viewMessageModal{{ $value->id }}">
                    <i class="mdi mdi-message-text me-1"></i>
                </button>


                <div class="modal fade" id="viewMessageModal{{ $value->id }}" tabindex="-1" aria-labelledby="viewMessageLabel{{ $value->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                        
                        <div class="modal-header">
                            <h5 class="modal-title" id="viewMessageLabel{{ $value->id }}">Order Message</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <p>🙏 Thank you, <strong>{{ $value->customer_name }}</strong>!</p>

                            <p>We truly appreciate your order with <strong>Quilon Pickles</strong>.<br>
                            Your bill (Bill No: <strong>QP-{{ str_pad($value->id, 4, '0', STR_PAD_LEFT) }}</strong>) is attached.</p>

                            <p>✨ Stay connected with us for new products and offers:</p>

                            <ul>
                                <li>📲 WhatsApp: 8891155404 </li>
                                <li>📸 Instagram: [@quilon_pickles ](https://www.instagram.com/quilon_pickles/) </li>
                                <li>📢 WhatsApp Channel: https://whatsapp.com/channel/0029VbBFQsS2kNFz32iZbT3f </li>
                            </ul>
                            <br>

                            <p>Looking forward to serving you again! 💚</p>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>

                        </div>
                    </div>
                </div>



            </td>
        </tr>
        @empty
        <tr>
            <th scope="row" colspan="9">No Data To List . . . </th>
        </tr>
        @endforelse
    </tbody>
</table>




<div class="d-flex justify-content-center">
    {!! $orders->links() !!}
</div>