@extends('frontend.layouts.app')

@section('contents')
    <!--Start hero slider-->
    @include('frontend.home.sections.hero-section')
    <!--End hero slider-->
    @include('frontend.home.sections.category-section')
    <!--End category slider-->
    @include('frontend.home.sections.banner-section')
    <!--End banners-->
    @include('frontend.home.sections.products-tab-section')
    <!--End Products Tabs Section-->
    @include('frontend.home.sections.banner-section-two')
    <!--End banners two -->
    @include('frontend.home.sections.flash-sale-section')
    <!--End Flash Sales-->
    @include('frontend.home.sections.new-arrival-section')
    <!-- new arrival end -->
    <section class="wsus__ctg mt-40">
        <div class="container">
            <a href="#" class="wsus__ctg_area">
                <img src="assets/imgs/cta_bg.png" alt="cta" class="img-fluid w-100" />
            </a>
        </div>
    </section>
    <!--CTA section end-->
    @include('frontend.home.sections.special-products-section')
    <!-- special products end -->
    @include('frontend.home.sections.four-col-products-section')
    <!--End 4 columns-->
@endsection
