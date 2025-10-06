@extends('adminlte::page')

@section('title', 'Shop Product Details')

@section('content_header')
    <h1>Shop Product Details</h1>
@stop

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

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Product Information</h3>
                </div>
                <div class="card-body">
                    <p><strong>Product:</strong> {{ $shopProduct->product->english_description }}</p>
                    <p><strong>Brand:</strong> {{ $shopProduct->product->brand ?? 'N/A' }}</p>
                    <p><strong>Barcode:</strong> {{ $shopProduct->product->barcode ?? 'N/A' }}</p>
                    <p><strong>Supplier Reference:</strong> {{ $shopProduct->product->supplier_reference ?? 'N/A' }}</p>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Shop Information</h3>
                </div>
                <div class="card-body">
                    <p><strong>Shop:</strong> {{ $shopProduct->shop->name }}</p>
                    <p><strong>Address:</strong> {{ $shopProduct->shop->address ?? 'N/A' }}</p>
                    <p><strong>Quantity in Stock:</strong> 
                        <span class="badge badge-{{ $shopProduct->quantity > 0 ? 'success' : 'secondary' }} badge-pill" style="font-size: 16px;">
                            {{ $shopProduct->quantity }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Product Images</h3>
                    @if($shopProduct->images && count($shopProduct->images) > 0)
                        <small class="text-muted">Click on any image to zoom</small>
                    @endif
                </div>
                <div class="card-body">
                    @if($shopProduct->images && count($shopProduct->images) > 0)
                        <div class="row">
                            @foreach($shopProduct->images as $index => $image)
                                <div class="col-6 col-md-4 mb-3">
                                    <div class="card image-card" style="cursor: pointer;">
                                        <img src="{{ asset('storage/' . $image) }}" 
                                             class="card-img-top zoomable-image" 
                                             style="height: 120px; object-fit: cover;"
                                             alt="Product Image"
                                             data-toggle="modal" 
                                             data-target="#imageModal"
                                             data-images='@json($shopProduct->image_urls)'
                                             data-current="{{ $index }}">
                                        <div class="card-body text-center p-2">
                                            <small class="text-muted">Image {{ $loop->iteration }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-image fa-3x mb-2"></i>
                            <p>No images available</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Notes</h3>
                </div>
                <div class="card-body">
                    @if($shopProduct->notes)
                        <p>{{ $shopProduct->notes }}</p>
                    @else
                        <p class="text-muted">No notes available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">Actions</h3>
        </div>
        <div class="card-body">
            <a href="{{ route('admin.shop-products.edit', $shopProduct) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.shop-products.by-shop', $shopProduct->shop) }}" class="btn btn-info">
                <i class="fas fa-store"></i> View All Products in This Shop
            </a>
            <a href="{{ route('admin.shop-products.by-product', $shopProduct->product) }}" class="btn btn-secondary">
                <i class="fas fa-box"></i> View This Product in All Shops
            </a>
            <form action="{{ route('admin.shop-products.destroy', $shopProduct) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </form>
            <a href="{{ route('admin.shop-products.index') }}" class="btn btn-light">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Product Images Gallery</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <div id="carouselExample" class="carousel slide" data-ride="carousel" data-interval="false">
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
                    
                    <!-- Image Counter and Navigation -->
                    <div class="mt-3 d-flex justify-content-between align-items-center">
                        <button class="btn btn-sm btn-outline-primary" id="prev-btn">
                            <i class="fas fa-chevron-left"></i> Previous
                        </button>
                        <small class="text-muted mx-3" id="image-counter">Image 1 of 1</small>
                        <button class="btn btn-sm btn-outline-primary" id="next-btn">
                            Next <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Close
                    </button>
                    <button type="button" class="btn btn-info" id="fullscreen-btn">
                        <i class="fas fa-expand"></i> Fullscreen
                    </button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
<style>
    .zoomable-image {
        transition: transform 0.2s;
        cursor: pointer;
    }
    .zoomable-image:hover {
        transform: scale(1.05);
    }
    .image-card:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        transition: box-shadow 0.2s;
    }
    .carousel-item img {
        max-height: 70vh;
        object-fit: contain;
        width: 100%;
    }
    .carousel-control-prev,
    .carousel-control-next {
        width: 5%;
        background: rgba(0,0,0,0.3);
    }
    .modal-xl {
        max-width: 95%;
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
            updateImageCounter(currentIndex, images.length);
        });
        
        // Update counter when carousel slides
        $('#carouselExample').on('slid.bs.carousel', function (e) {
            const activeIndex = $(this).find('.carousel-item.active').index();
            const totalItems = $(this).find('.carousel-item').length;
            updateImageCounter(activeIndex, totalItems);
        });
        
        // Custom navigation buttons
        $('#prev-btn').on('click', function() {
            $('#carouselExample').carousel('prev');
        });
        
        $('#next-btn').on('click', function() {
            $('#carouselExample').carousel('next');
        });
        
        // Fullscreen functionality
        $('#fullscreen-btn').on('click', function() {
            const modal = $('#imageModal');
            const carousel = $('#carouselExample');
            
            if (!modal.hasClass('fullscreen')) {
                modal.addClass('fullscreen');
                modal.find('.modal-dialog').addClass('modal-fullscreen');
                $(this).html('<i class="fas fa-compress"></i> Exit Fullscreen');
            } else {
                modal.removeClass('fullscreen');
                modal.find('.modal-dialog').removeClass('modal-fullscreen');
                $(this).html('<i class="fas fa-expand"></i> Fullscreen');
            }
        });
        
        // Keyboard navigation
        $(document).on('keydown', function(e) {
            if ($('#imageModal').is(':visible')) {
                if (e.key === 'ArrowLeft') {
                    $('#carouselExample').carousel('prev');
                } else if (e.key === 'ArrowRight') {
                    $('#carouselExample').carousel('next');
                } else if (e.key === 'Escape') {
                    if ($('#imageModal').hasClass('fullscreen')) {
                        $('#imageModal').removeClass('fullscreen');
                        $('#imageModal .modal-dialog').removeClass('modal-fullscreen');
                        $('#fullscreen-btn').html('<i class="fas fa-expand"></i> Fullscreen');
                    } else {
                        $('#imageModal').modal('hide');
                    }
                } else if (e.key === 'f' || e.key === 'F') {
                    $('#fullscreen-btn').click();
                }
            }
        });
        
        function updateImageCounter(current, total) {
            $('#image-counter').text(`Image ${current + 1} of ${total}`);
        }
    });
</script>
@stop