@extends('adminlte::page')

@section('title', 'Shop Products')
<style>
    .modal-fullscreen {
    width: 100vw;
    max-width: none;
    height: 100vh;
    margin: 0;
}
.modal-fullscreen .modal-content {
    height: 100vh;
    border: 0;
    border-radius: 0;
}
.modal-fullscreen .carousel-item img {
    max-height: 90vh;
}
</style>

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Shop Products</h1>
        <a href="{{ route('admin.shop-products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Shop Product
        </a>
    </div>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Shop</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Images</th>
                            <th>Notes</th>
                            <th>Last Updated</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shopProducts as $shopProduct)
                            <tr>
                                <td><strong>#{{ $shopProduct->id }}</strong></td>
                                <td>
                                    <strong>{{ $shopProduct->shop->name }}</strong>
                                    <br><small class="text-muted">ID: {{ $shopProduct->shop_id }}</small>
                                </td>
                                <td>
                                    <strong>{{ $shopProduct->product->english_description }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        Brand: {{ $shopProduct->product->brand ?? 'N/A' }}
                                        @if($shopProduct->product->barcode)
                                            <br>Barcode: {{ $shopProduct->product->barcode }}
                                        @endif
                                    </small>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $shopProduct->quantity > 0 ? 'success' : 'secondary' }} badge-pill" style="font-size: 14px;">
                                        {{ $shopProduct->quantity }}
                                    </span>
                                </td>
                                <td>
                                    @if($shopProduct->images && count($shopProduct->images) > 0)
                                        <div class="d-flex flex-wrap gap-1" style="max-width: 150px;">
                                            @foreach(array_slice($shopProduct->images, 0, 3) as $index => $image)
                                                <img src="{{ asset('storage/' . $image) }}" 
                                                     class="img-thumbnail zoomable-image" 
                                                     style="width: 40px; height: 40px; object-fit: cover; cursor: pointer;"
                                                     title="Click to zoom"
                                                     data-toggle="modal" 
                                                     data-target="#imageModal"
                                                     data-images='@json($shopProduct->image_urls)'
                                                     data-current="{{ $index }}">
                                            @endforeach
                                            @if(count($shopProduct->images) > 3)
                                                <span class="badge badge-light" 
                                                      data-toggle="modal" 
                                                      data-target="#imageModal"
                                                      data-images='@json($shopProduct->image_urls)'
                                                      data-current="0"
                                                      style="cursor: pointer;">
                                                    +{{ count($shopProduct->images) - 3 }}
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">No images</span>
                                    @endif
                                </td>
                                <td>
                                    @if($shopProduct->notes)
                                        <small title="{{ $shopProduct->notes }}">
                                            {{ Str::limit($shopProduct->notes, 30) }}
                                        </small>
                                    @else
                                        <span class="text-muted">No notes</span>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ $shopProduct->updated_at->format('M j, Y H:i') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.shop-products.show', $shopProduct) }}" 
                                           class="btn btn-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.shop-products.edit', $shopProduct) }}" 
                                           class="btn btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.shop-products.destroy', $shopProduct) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" title="Delete"
                                                    onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-boxes fa-3x mb-3"></i>
                                        <h4>No Shop Products Found</h4>
                                        <p>Start by creating your first shop product assignment.</p>
                                        <a href="{{ route('admin.shop-products.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Create Shop Product
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($shopProducts->hasPages())
                <div class="mt-3">
                    {{ $shopProducts->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Product Images</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <div id="carouselExample" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner" id="carousel-inner">
                            <!-- Images will be loaded here dynamically -->
                        </div>
                        
                        <!-- Carousel Controls -->
                        <a class="carousel-control-prev" href="#carouselExample" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExample" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                    
                    <!-- Image Counter -->
                    <div class="mt-3">
                        <small class="text-muted" id="image-counter">Image 1 of 1</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
<style>
    .zoomable-image {
        transition: transform 0.2s;
    }
    .zoomable-image:hover {
        transform: scale(1.2);
    }
    .carousel-item img {
        max-height: 500px;
        object-fit: contain;
        width: 100%;
    }
    .carousel-control-prev,
    .carousel-control-next {
        width: 5%;
    }
    .badge[data-toggle="modal"]:hover {
        background-color: #e9ecef !important;
        transform: scale(1.05);
    }
</style>
@stop

@section('js')
<script>
    $(document).ready(function() {
        // Image modal functionality
        $('#imageModal').on('show.bs.modal', function (event) {
            const trigger = $(event.relatedTarget);
            const images = JSON.parse(trigger.data('images'));
            const currentIndex = parseInt(trigger.data('current')) || 0;
            
            const carouselInner = $('#carousel-inner');
            carouselInner.empty();
            
            // Add images to carousel
            images.forEach((image, index) => {
                const item = $('<div class="carousel-item"></div>');
                if (index === currentIndex) {
                    item.addClass('active');
                }
                
                const img = $('<img>').attr('src', image)
                    .addClass('d-block w-100')
                    .attr('alt', `Product Image ${index + 1}`);
                
                item.append(img);
                carouselInner.append(item);
            });
            
            // Update image counter
            $('#image-counter').text(`Image ${currentIndex + 1} of ${images.length}`);
        });
        
        // Update counter when carousel slides
        $('#carouselExample').on('slid.bs.carousel', function (e) {
            const activeIndex = $(this).find('.carousel-item.active').index();
            const totalItems = $(this).find('.carousel-item').length;
            $('#image-counter').text(`Image ${activeIndex + 1} of ${totalItems}`);
        });
        
        // Keyboard navigation
        $(document).on('keydown', function(e) {
            if ($('#imageModal').is(':visible')) {
                if (e.key === 'ArrowLeft') {
                    $('#carouselExample').carousel('prev');
                } else if (e.key === 'ArrowRight') {
                    $('#carouselExample').carousel('next');
                } else if (e.key === 'Escape') {
                    $('#imageModal').modal('hide');
                }
            }
        });
    });
</script>
@stop