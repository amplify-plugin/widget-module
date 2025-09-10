<div {!! $htmlAttributes !!}>
    <div class="row">
        <div class="col-lg-3 col-md-4  order-md-1">
            <div class="padding-top-3x hidden-md-up"></div>
            <div class="card rounded-bottom-0 mb-0" data-filter-list="#components-list">
                <div class="card-body pb-3">
                    <div class="widget mb-4">
                        <div class="input-group form-group">
                            <span class="input-group-btn">
                              <button class="btn" type="submit" disabled="">
                                  <i class="icon-search"></i>
                              </button>
                            </span>
                            <input class="form-control" type="text" placeholder="Search FAQ Category">
                        </div>
                    </div>
                    <div class="mx-2 row justify-content-between">
                        <div class="custom-control custom-radio custom-control-inline">
                            <input class="custom-control-input" type="radio" id="filter-all" name="compFilter"
                                   value="all"
                                   checked="">
                            <label class="custom-control-label" for="filter-all">ALL</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input class="custom-control-input" type="radio" id="filter-ae" name="compFilter"
                                   value="ae">
                            <label class="custom-control-label" for="filter-ae">A-E</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input class="custom-control-input" type="radio" id="filter-fj" name="compFilter"
                                   value="fj">
                            <label class="custom-control-label" for="filter-fj">F-J</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input class="custom-control-input" type="radio" id="filter-ko" name="compFilter"
                                   value="ko">
                            <label class="custom-control-label" for="filter-ko">K-O</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input class="custom-control-input" type="radio" id="filter-pt" name="compFilter"
                                   value="pt">
                            <label class="custom-control-label" for="filter-pt">P-T</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input class="custom-control-input" type="radio" id="filter-uz" name="compFilter"
                                   value="uz">
                            <label class="custom-control-label" for="filter-uz">U-Z</label>
                        </div>
                    </div>
                </div>
            </div>
            <nav class="list-group" id="components-list">
                @foreach($faqs as $faqCategory)
                    <a class="list-group-item list-group-item-action @if($current->id == $faqCategory->id) active @endif @if($faqs->first() == $faqCategory) rounded-top-0 @endif"
                       href="{{$faqCategory->link }}"
                       data-filter-item="{{$faqCategory->group}}">
                        {{ $faqCategory->name }}
                    </a>
                @endforeach
            </nav>
        </div>
        <div class="col-lg-9 col-md-8 order-md-2">
            <div class="accordion" id="accordion" role="tablist">
                @forelse(($current->items ?? []) as $index => $faq)
                    <div class="card">
                        <div class="card-header" role="tab"
                             onclick="handleFaqCounterNotification(this, false);"
                             data-route="{{ route('frontend.faqs.stats-count', [$faq->id, 'no-views']) }}">
                            <h6>
                                <a href="#collapse-{{$index}}" data-toggle="collapse" class="collapsed"
                                   aria-expanded="false">
                                    {{ $faq->question }}
                                </a>
                            </h6>
                        </div>
                        <div class="collapse" id="collapse-{{$index}}" data-parent="#accordion" role="tabpanel"
                             style="">
                            <div class="card-body">
                                {!! $faq->response !!}
                            </div>
                            <div
                                    class="card-footer d-flex justify-content-center justify-content-md-end border-top-0 bg-transparent">
                                <div class="d-flex">
                                    <div class="btn btn-sm faq-btn"
                                         onclick="handleFaqCounterNotification(this)"
                                         data-toggle="tooltip"
                                         title="Useful"
                                         data-route="{{ route('frontend.faqs.stats-count', [$faq->id, 'useful']) }}">
                                        <i class="pe-7s-like2 font-weight-bold mr-1 faq-reaction"></i>
                                        {{ $faq->useful ?? 0 }}
                                    </div>
                                    <div class="btn btn-sm faq-btn"
                                         onclick="handleFaqCounterNotification(this)"
                                         data-toggle="tooltip"
                                         title="Not Useful"
                                         data-route="{{ route('frontend.faqs.stats-count', [$faq->id, 'not-useful']) }}">
                                        <i class="pe-7s-like2 font-weight-bold mr-1 faq-reaction"
                                           style="transform: rotateX(180deg)"></i>
                                        {{ $faq->not_useful ?? 0 }}
                                    </div>
                                    <div class="btn btn-sm faq-btn"
                                         data-toggle="tooltip"
                                         title="Views">
                                        <i class="pe-7s-look font-weight-bold mr-1 faq-reaction"></i>
                                        {{ $faq->no_views ?? 0}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
        </div>
    </div>
</div>
