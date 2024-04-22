<div class="stm_lms_single_bundle_card__rating heading_font">
    <div class="average-rating-stars__top">
        <div class="star-rating">
            <span v-bind:style="{'width': bundleRating(bundle).percent + '%'}">
                <strong class="rating">bundleRating(bundle).average</strong>
            </span>
        </div>
        <div class="average-rating-stars__av heading_font">
            {{bundleRating(bundle).average}} ({{bundleRating(bundle).count}})
        </div>
    </div>
</div>

<div class="stm_lms_single_bundle_card__price heading_font">
    <span class="bundle_price">{{bundle.price}}</span>
    <span class="bundle_courses_price">{{bundlePrice(bundle)}}</span>
</div>