<?php
/**
 * The template for displaying services pages.
 *
 * Template Name: Магазин
 *
 * @package storefront
 */
$frontpage_id = get_option('page_on_front');
get_header(); ?>
    <!-- page -->
    <main class="page _page-container">
        <?
        //if(get_field('slaider')):
        //      rs_slider_store();
        // endif; слайдер
        ?> 
        <!-- rs-store -->
        <? echo get_field('map', get_the_ID(), false, false); ?>
        <section class="rs-store">
            <div class="rs-store_container">
                <div class="rs-store__contact">
                    <h3 class="xxl-medium-title"><?=get_field('city') ?></h3>
                    <div class="rs-store__contact_list">
                        <div class="rs-store__contact_item">
                            <h5 class="sm-semibold-title">Каменоостровский, 32</h5>
                                                        <h5 class="sm-semibold-title"><? the_field('address') ?></h5>

                            <? $data = get_field('online_shop'); ?>
                            <ul>
                                <li><a href="tel:<?=preg_replace('~\D+~','',$data['phone'])?>"><?=$data['phone']?></a></li>
                                <li><span><?=$data['worktime']?></span></li>
                            </ul>
                            <p class="section-text"><?_e('Opening hours on holidays:','storefront')?> <br>
                                <?=$data['worktime_out_shop']?></p>
                        </div>
                        <!-- <div class="rs-store__contact_item">
                            <h5 class="sm-semibold-title"><? the_field('address') ?></h5>
                            <? $data = get_field('shop'); ?>
                            <ul>
                                <li><a href="tel:<?=preg_replace('~\D+~','',$data['phone'])?>"><?=$data['phone']?></a></li>
                                <li><span><?=$data['worktime']?></span></li>
                            </ul>
                            <p class="section-text"><?_e('Opening hours on holidays:','storefront')?> <br>
                                <?=$data['worktime_out']?></p>
                        </div> -->
                    </div>
                </div>
                <div class="rs-store__map">
                    <div class="map" id="map"></div>
                </div>
                <div class="rs-store__about">
                    <h3 class="xxl-medium-title"><?
                    //the_title()?></h3>
                    <p class="section-text"><?
                    //the_content()?></p>
                </div>
            </div>
        </section>
        <!-- /rs-store -->


     <?php
        // Повторитель с блоками контента и фотографиями
        if( have_rows('content_blocks') ): ?>
            <!-- rs-content-blocks -->
            <section class="rs-content-blocks">
                <div class="rs-store_container">
                    <?php while( have_rows('content_blocks') ): the_row(); 
                        $title = get_sub_field('block_title');
                        $description = get_sub_field('block_description');
                    ?>
                            <?php if($title): ?>
                                <h3 class="xxl-medium-title"><?php echo $title; ?></h3>
                            <?php endif; ?>
                            
                            <?php if($description): ?>
                                <p class="section-text"><?php echo $description; ?></p>
                            <?php endif; ?>
                            
                            <?php if( have_rows('photos') ): ?>
                                <div class="rs-photos-list">
                                    <?php while( have_rows('photos') ): the_row(); 
                                        $photo = get_sub_field('photo');
                                        if($photo): ?>
                                            <div class="rs-photo-item">
                                                <img src="<?php echo esc_url($photo['url']); ?>" 
                                                     alt="<?php echo esc_attr($photo['alt'] ? $photo['alt'] : $title); ?>" />
                                            </div>
                                        <?php endif; ?>
                                    <?php endwhile; ?>
                                </div>
                            <?php endif; ?>
                    <?php endwhile; ?>
                </div>
            </section>
            <!-- /rs-content-blocks -->
        <?php endif; ?>

       

        <?php if(get_field('iftame_tour')) { ?>
        <!-- rs-tour -->
        <section class="rs-tour">
            <div class="rs-tour_container">
                <div class="rs-tour__list">
                    <div class="rs-tour__item">
                        <?php echo get_field('iftame_tour', get_the_ID(), false, false) ?>
                    </div>
                </div>
            </div>
        </section>
        <!-- /rs-tour -->
        <?php } ?>
    </main>
    <!-- /page -->

    <!-- rs-modal -->
    <div class="rs-modal">
        <div class="modal fade" tabindex="-1" id="rs-modal-store">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- rs-form -->
                    <section class="rs-form">
                        <div class="rs-form__container">
                            <h2 class="section-title"><?=get_field('title_form')?></h2>
                            <div class="rs-form__wrapper">
                                <form class="form" id="rs_store_form" data-page="<?=get_the_ID()?>">
                                    <div class="form-wrapper">
                                        <div class="form-block">
                                            <div class="form-fields">
                                                <div class="form-field">
                                                    <input autocomplete="off" type="text" name="user-name" placeholder="<?_e('Your name','storefront')?>"
                                                           class="rs-input">
                                                </div>
                                                <div class="form-field">
                                                    <input autocomplete="off" type="text" name="user-email" placeholder="<?_e('E-mail','storefront')?>"
                                                           class="rs-input">
                                                </div>
                                            </div>
                                            <div class="form-field form-field_select">
                                                <?
                                                $category_form = get_field('category_form');
                                                if($category_form && $category_form!='') {
                                                    $categories = preg_split('/\r\n|[\r\n]/', $category_form);
                                                    if($categories) {
                                                        ?>
                                                        <select name="user-category" class="rs-input">
                                                            <option disabled selected><?_e('Category','storefront')?></option>
                                                            <?
                                                            foreach($categories as $cat) {
                                                                ?><option><?=$cat?></option>
                                                            <? } ?>
                                                        </select>
                                                        <?
                                                    }
                                                } ?>
                                            </div>
                                            <div class="form-field">
                                                <textarea autocomplete="off" name="user-message"
                                                          placeholder="<?_e('Ask a question or write your wishes and suggestions','storefront')?>"
                                                          class="rs-input"></textarea>
                                            </div>
                                            <div class="form-field">
                                                <label class="input-file rs-input">
                                                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 119 330.6" style="enable-background:new 0 0 119 330.6;" xml:space="preserve"><path d="M77.7,318c-0.4,0.4-0.8,0.8-1.2,1.2c-7.8,7.2-18.1,11.1-29,11.1c-10.9,0-21.3-3.9-29-11.1c-7.9-7.3-12.3-17-12.3-27.4l0-240c0-13.3,5.6-25.7,15.7-35.1c10-9.2,23.3-14.3,37.4-14.3c14.1,0,27.3,5.1,37.4,14.3c10.1,9.3,15.7,21.8,15.7,35.1l0,180.9c0,3.4-2.7,6.1-6.1,6.1c-3.4,0-6.1-2.7-6.1-6.1l0-180.9c0-9.8-4.2-19.1-11.8-26.1c-7.7-7.1-18.1-11-29.1-11c-11,0-21.3,3.9-29.1,11c-7.6,7-11.8,16.3-11.8,26.1l0,240c0,14.5,13.1,26.3,29.1,26.3c7.9,0,15.3-2.8,20.8-7.9c5.4-5,8.3-11.5,8.3-18.4l0-195.2c0-8.5-7.8-15.5-17.4-15.5c-4.7,0-9.2,1.7-12.5,4.7c-3.2,2.9-4.9,6.7-4.9,10.8l0,136c0,3.4-2.7,6.1-6.1,6.1c-3.4,0-6.1-2.7-6.1-6.1l0-136c0-7.5,3.1-14.5,8.9-19.8c5.6-5.1,12.9-7.9,20.7-7.9c16.3,0,29.6,12.4,29.6,27.7l0,195.2C88.8,301.6,84.9,310.9,77.7,318z"/></svg>
                                                    <input type="file" name="user-attachment" multiple>
                                                    <span><?_e('Attach file','storefront')?></span>
                                                    <span class="default hidden"><?_e('Attach file','storefront')?></span>
                                                    <span class="multi hidden"> <?_e('files','storefront')?></span>
                                                </label>
                                                <input type="hidden" value="">
                                            </div>
                                            <div class="form-messages">
                                                <div class="form-message success" style="display:none;">
                                                    <span><?_e('Thank you! Your message has been sent','storefront')?></span>
                                                </div>
                                                <div class="form-message error" style="display:none;">
                                                    <span><?_e('One or more fields are filled out incorrectly. Please check the details!','storefront')?></span>
                                                </div>
                                            </div>
                                            <div class="form-footer">
                                                <div class="form-agreement">
                                                    <?=get_field('text_form')?>
                                                </div>
                                                <div class="form-button">
                                                    <button type="submit" class="rs-btn _background-btn _black-btn"><?_e('Submit','storefront')?></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </section>
                    <!-- /rs-form -->
                </div>
            </div>
        </div>
    </div>

    <style>
.rs-photos-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin-top: 30px;
}

.rs-photo-item {
    width: 100%;
}

.rs-photo-item{
    
}

.rs-photo-item img {
    width: 100%;
    height: auto;
    display: block;
}</style>
    <!-- /rs-modal -->
<?php
get_footer();

