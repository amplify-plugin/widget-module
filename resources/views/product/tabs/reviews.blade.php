@push('title')
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#{{ $entry['name'] }}" role="tab">
            {{ __($entry['label']) }}
        </a>
    </li>
@endpush

@push('content')
    <div class="tab-pane fade" id="{{ $entry['name'] }}" role="tabpanel"
         aria-labelledby="{{ $entry['name'] }}">
        <!-- Review-->
        <div class="comment">
            <div class="comment-author-ava"><img src="img/reviews/01.jpg" alt="Review author"></div>
            <div class="comment-body">
                <div class="comment-header d-flex flex-wrap justify-content-between">
                    <h4 class="comment-title">Average quality for the price</h4>
                    <div class="mb-2">
                        <div class="rating-stars"><i class="icon-star filled"></i><i class="icon-star filled"></i><i
                                    class="icon-star filled"></i><i class="icon-star"></i><i class="icon-star"></i>
                        </div>
                    </div>
                </div>
                <p class="comment-text">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis
                    praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint
                    occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi,
                    id est laborum et dolorum fuga.</p>
                <div class="comment-footer"><span class="comment-meta">Francis Burton</span></div>
            </div>
        </div>
        <!-- Review-->
        <div class="comment">
            <div class="comment-author-ava"><img src="img/reviews/02.jpg" alt="Review author"></div>
            <div class="comment-body">
                <div class="comment-header d-flex flex-wrap justify-content-between">
                    <h4 class="comment-title">My husband love his new...</h4>
                    <div class="mb-2">
                        <div class="rating-stars"><i class="icon-star filled"></i><i class="icon-star filled"></i><i
                                    class="icon-star filled"></i><i class="icon-star filled"></i><i
                                    class="icon-star filled"></i>
                        </div>
                    </div>
                </div>
                <p class="comment-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
                    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
                    exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                <div class="comment-footer"><span class="comment-meta">Maggie Scott</span></div>
            </div>
        </div>
        <!-- Review-->
        <div class="comment">
            <div class="comment-author-ava"><img src="img/reviews/03.jpg" alt="Review author"></div>
            <div class="comment-body">
                <div class="comment-header d-flex flex-wrap justify-content-between">
                    <h4 class="comment-title">Soft, comfortable, quite durable...</h4>
                    <div class="mb-2">
                        <div class="rating-stars"><i class="icon-star filled"></i><i class="icon-star filled"></i><i
                                    class="icon-star filled"></i><i class="icon-star filled"></i><i
                                    class="icon-star"></i>
                        </div>
                    </div>
                </div>
                <p class="comment-text">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium
                    doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi
                    architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit
                    aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem
                    sequi nesciunt.</p>
                <div class="comment-footer"><span class="comment-meta">Jacob Hammond</span></div>
            </div>
        </div>
        <!-- Review Form-->
        <h5 class="mb-30 padding-top-1x">Leave Review</h5>
        <form class="row" method="post" action="{{ frontendHomeURL() }}">
            @csrf
            <div class="col-sm-6">
                {!! Form::rText('name', __('Your Name'), null, true, ['placeholder' => __('Enter Your Name')]) !!}
            </div>
            <div class="col-sm-6">
                {!! Form::rEmail('email', __('Your Email'), null, true, ['placeholder' => __('Enter Your Email')]) !!}
            </div>
            <div class="col-sm-6">
                {!! Form::rText('subject', __('Subject'), null, true, ['placeholder' => __('Enter Subject')]) !!}
            </div>
            <div class="col-sm-6">
                {!! Form::rSelect('rating', __('Rating'), ['5' => '5 Stars', '4' => '4 Stars','3' => '3 Stars','2' => '2 Stars','1' => '1 Star'], 5, true) !!}
            </div>
            <div class="col-12">
                {!! Form::rTextarea('review', __('Review'), null, true, ['placeholder' => __('Type your review about this product...'), 'rows' => 8]) !!}
            </div>
            <div class="col-12 text-center text-md-right">
                <button class="btn btn-primary" type="submit">Submit Review</button>
            </div>
        </form>
    </div>
@endpush