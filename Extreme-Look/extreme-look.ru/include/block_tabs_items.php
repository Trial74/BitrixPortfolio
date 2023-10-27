<div class="tabs-wrap">
    <div class="tabs__list" data-entity="main-tabs">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="tabs__scroll">
                        <ul class="tabs__tabs">
                            <li class="tabs__tab" data-entity="tab" data-value="description"><?=($_SESSION['MY_PARAMS'][3] ? 'Описание' : 'Description')?></li>
                            <li class="tabs__tab" data-entity="tab" data-value="specifications"><?=($_SESSION['MY_PARAMS'][3] ? 'Характеристики' : 'Specifications')?></li>
                            <li class="tabs__tab" data-entity="tab" data-value="video"><?=($_SESSION['MY_PARAMS'][3] ? 'Видео' : 'Video')?></li>
                            <li class="tabs__tab" data-entity="tab" data-value="reviews"><?=($_SESSION['MY_PARAMS'][3] ? 'Отзывы о товаре' : 'Product Reviews')?></li>
                            <li class="tabs__tab" data-entity="tab" data-value="question"><?=($_SESSION['MY_PARAMS'][3] ? 'Задать вопрос' : 'Ask a Question')?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tabs__content" data-entity="main-tabs-content">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="tabs__box" data-entity="tab-content" data-value="description">
                        123
                    </div>
                    <div class="tabs__box" data-entity="tab-content" data-value="specifications">
                        1234
                    </div>
                    <div class="tabs__box" data-entity="tab-content" data-value="video">
                        1235
                    </div>
                    <div class="tabs__box" data-entity="tab-content" data-value="reviews">
                        1236
                    </div>
                    <div class="tabs__box" data-entity="tab-content" data-value="question">
                        1237
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>