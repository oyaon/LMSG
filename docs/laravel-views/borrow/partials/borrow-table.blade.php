<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Book</th>
                        <th>Request Date</th>
                        <th>Issue Date</th>
                        <th>Return Date</th>
                        <th>Status</th>
                        <th>Fine</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($borrows as $borrow)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $borrow->book->cover_image_url }}" alt="{{ $borrow->book->name }}" class="img-thumbnail me-2" style="width: 50px; height: 70px; object-fit: cover;">
                                    <div>
                                        <h6 class="mb-0">{{ $borrow->book->name }}</h6>
                                        <small class="text-muted">{{ $borrow->book->author }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $borrow->created_at->format('M d, Y') }}</td>
                            <td>{{ $borrow->issue_date ? $borrow->issue_date->format('M d, Y') : '-' }}</td>
                            <td>
                                @if($borrow->return_date)
                                    @if($borrow->status == 'Issued' && $borrow->return_date->isPast())
                                        <span class="overdue">{{ $borrow->return_date->format('M d, Y') }} (Overdue)</span>
                                    @else
                                        {{ $borrow->return_date->format('M d, Y') }}
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($borrow->status == 'Requested')
                                    <span class="badge bg-warning text-dark">Requested</span>
                                @elseif($borrow->status == 'Issued')
                                    <span class="badge bg-success">Issued</span>
                                @elseif($borrow->status == 'Returned')
                                    <span class="badge bg-info">Returned</span>
                                @else
                                    <span class="badge bg-danger">Declined</span>
                                @endif
                            </td>
                            <td>
                                @if($borrow->fine)
                                    <span class="text-danger">${{ number_format($borrow->fine, 2) }}</span>
                                @elseif($borrow->status == 'Issued' && $borrow->return_date && $borrow->return_date->isPast())
                                    @php
                                        $daysOverdue = now()->diffInDays($borrow->return_date, false);
                                        $estimatedFine = abs($daysOverdue) * 10; // $10 per day
                                    @endphp
                                    <span class="text-danger">${{ number_format($estimatedFine, 2) }} (Estimated)</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($borrow->status == 'Requested')
                                    <form action="{{ route('borrow.cancel', $borrow) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to cancel this request?')">
                                            <i class="fas fa-times"></i> Cancel
                                        </button>
                                    </form>
                                @elseif($borrow->status == 'Issued')
                                    <form action="{{ route('borrow.return', $borrow) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-primary" onclick="return confirm('Are you sure you want to return this book?')">
                                            <i class="fas fa-undo"></i> Return
                                        </button>
                                    </form>
                                @endif
                                
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#borrowModal{{ $borrow->id }}">
                                    <i class="fas fa-eye"></i> Details
                                </button>
                            </td>
                        </tr>
                        
                        <!-- Borrow Details Modal -->
                        <div class="modal fade" id="borrowModal{{ $borrow->id }}" tabindex="-1" aria-labelledby="borrowModalLabel{{ $borrow->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="borrowModalLabel{{ $borrow->id }}">Borrow Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <img src="{{ $borrow->book->cover_image_url }}" alt="{{ $borrow->book->name }}" class="img-fluid rounded">
                                            </div>
                                            <div class="col-md-8">
                                                <h4>{{ $borrow->book->name }}</h4>
                                                <p class="text-muted">{{ $borrow->book->author }}</p>
                                                
                                                <div class="row mb-2">
                                                    <div class="col-md-4 fw-bold">Category:</div>
                                                    <div class="col-md-8">{{ $borrow->book->category }}</div>
                                                </div>
                                                
                                                <div class="row mb-2">
                                                    <div class="col-md-4 fw-bold">Status:</div>
                                                    <div class="col-md-8">
                                                        @if($borrow->status == 'Requested')
                                                            <span class="badge bg-warning text-dark">Requested</span>
                                                        @elseif($borrow->status == 'Issued')
                                                            <span class="badge bg-success">Issued</span>
                                                        @elseif($borrow->status == 'Returned')
                                                            <span class="badge bg-info">Returned</span>
                                                        @else
                                                            <span class="badge bg-danger">Declined</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <div class="row mb-2">
                                                    <div class="col-md-4 fw-bold">Request Date:</div>
                                                    <div class="col-md-8">{{ $borrow->created_at->format('F d, Y') }}</div>
                                                </div>
                                                
                                                <div class="row mb-2">
                                                    <div class="col-md-4 fw-bold">Issue Date:</div>
                                                    <div class="col-md-8">{{ $borrow->issue_date ? $borrow->issue_date->format('F d, Y') : '-' }}</div>
                                                </div>
                                                
                                                <div class="row mb-2">
                                                    <div class="col-md-4 fw-bold">Return Date:</div>
                                                    <div class="col-md-8">
                                                        @if($borrow->return_date)
                                                            @if($borrow->status == 'Issued' && $borrow->return_date->isPast())
                                                                <span class="overdue">{{ $borrow->return_date->format('F d, Y') }} (Overdue)</span>
                                                            @else
                                                                {{ $borrow->return_date->format('F d, Y') }}
                                                            @endif
                                                        @else
                                                            -
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <div class="row mb-2">
                                                    <div class="col-md-4 fw-bold">Fine:</div>
                                                    <div class="col-md-8">
                                                        @if($borrow->fine)
                                                            <span class="text-danger">${{ number_format($borrow->fine, 2) }}</span>
                                                        @elseif($borrow->status == 'Issued' && $borrow->return_date && $borrow->return_date->isPast())
                                                            @php
                                                                $daysOverdue = now()->diffInDays($borrow->return_date, false);
                                                                $estimatedFine = abs($daysOverdue) * 10; // $10 per day
                                                            @endphp
                                                            <span class="text-danger">${{ number_format($estimatedFine, 2) }} (Estimated)</span>
                                                        @else
                                                            -
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        @if($borrow->book->description)
                                            <div class="mt-4">
                                                <h5>Book Description</h5>
                                                <p>{{ $borrow->book->description }}</p>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        @if($borrow->status == 'Requested')
                                            <form action="{{ route('borrow.cancel', $borrow) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel this request?')">
                                                    <i class="fas fa-times me-2"></i> Cancel Request
                                                </button>
                                            </form>
                                        @elseif($borrow->status == 'Issued')
                                            <form action="{{ route('borrow.return', $borrow) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to return this book?')">
                                                    <i class="fas fa-undo me-2"></i> Return Book
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>