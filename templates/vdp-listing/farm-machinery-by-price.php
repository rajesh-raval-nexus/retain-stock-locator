<div class="container gfam-why-choose-section my-5 p-lg-5 p-3 bg-white">
    <h2 class="mb-3 text-lg-start text-center d-lg-block d-flex flex-column">
        <span class="fw-bold text-dark">FARM MACHINERY</span>
        <span class="fw-bold text-danger">BY PRICE</span>
    </h2>
    <div class="row justify-content-center">
        <div class="col-md-4 col-10 my-2">
            <button type="text" class="gfam-price-btn w-100 text-center block-price-filter" data-filter-type='under' data-filter-price=25000 >Under $25,000</button>
        </div>
        <div class="col-md-4 col-10 my-2">
            <button type="text" class="gfam-price-btn w-100 text-center block-price-filter" data-filter-type='under' data-filter-price=35000 >Under $35,000</button>
        </div>
        <div class="col-md-4 col-10 my-2">
            <button type="text" class="gfam-price-btn w-100 text-center block-price-filter" data-filter-type='above' data-filter-price=45000 >Above $45,000</button>
        </div>
    </div>
</div>

<?php echo "<pre>";
print_r($filters);
?>