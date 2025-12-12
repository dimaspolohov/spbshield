<?php
/**
 * The template for displaying services pages.
 *
 * Template Name: Клиентам
 *
 * @package storefront
 */
get_header(); ?>
<!-- rs-client-info -->
<section class="rs-client-info">
	<div class="rs-client-info__container">
		<h2 class="section-title"><? the_title()?></h2>
		<div data-tabs="992" class="rs-client-info__tabs tabs">
			<nav data-tabs-titles class="tabs__navigation">
				<div class="tabs__navigation__sticky">
					<?
					$cyr = ['Љ', 'Њ', 'Џ', 'џ', 'ш', 'ђ', 'ч', 'ћ', 'ж', 'љ', 'њ', 'Ш', 'Ђ', 'Ч', 'Ћ', 'Ж','Ц','ц', 'а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п', 'р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я', 'А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П', 'Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я',' '
					];
					$lat = ['lj', 'nj', 'dž', 'dž', 'š', 'đ', 'č', 'ć', 'ž', 'lj', 'nj', 'š', 'đ', 'č', 'ć', 'ž','c','c', 'a','b','v','g','d','e','io','zh','z','i','y','k','l','m','n','o','p', 'r','s','t','u','f','h','ts','ch','sh','sht','a','i','y','e','yu','ya', 'a','b','v','g','d','e','io','zh','z','i','y','k','l','m','n','o','p', 'r','s','t','u','f','h','ts','ch','sh','sht','a','i','y','e','yu','ya','_'
					];
					if( have_rows('tabs') ):
						$key=0;
						while( have_rows('tabs') ) : the_row();
							$title = get_sub_field('title');
							$hash = preg_replace ("/[^a-zA-ZА-Яа-я0-9\s]/","",$title);
							$hash = strtolower( str_replace($cyr, $lat, $title) );
							?><button type="button" class="tabs__title <?//=$key==0?'_tab-active':''?>" data-hash="#<?=$hash?>"><?=$title?> <i class="tabs__icon"></i></button><?
							$key++;
						endwhile;
					endif;
					?>
					<button type="button" class="tabs__title" data-hash="#privacy_policy"><?=get_field('title_privacy_policy')?> <i class="tabs__icon"></i></button>
					<button type="button" class="tabs__title" data-hash="#terms_of_use"><?=get_field('title_terms_of_use')?> <i class="tabs__icon"></i></button>
				</div>
			</nav>
			<div class="rs-client-info__wrapper">
				<div data-tabs-body class="tabs__content">
				<?
				if( have_rows('tabs') ):
                    $tabs=get_field('tabs');
				    foreach ($tabs as $key => $tab):
                        $title = $tab['title_tab']?$tab['title_tab']:$tab['title'];
				        $section_text = $tab['section-text']?$tab['section-text']:'';
				    ?>
                    <div class="tabs__body" <?=$key>0?'hidden':''?>>
                        <h2 class="section-title"><?=$title?></h2>
                        <?
                        if(!empty($section_text)): ?>
                            <p class="section-text"><?=$section_text?></p>
                        <?  endif;
                        if(is_array($tab["header"]) && $tab["header"][0]['top'] && !empty($tab["header"]) ):
                        $header=$tab["header"];
                        foreach($header as $item): ?>
                        <div class="rs-client-info__item">
                            <? $title = $item['title'];
                            if($title) {
                                ?><h4 class="s-bold-title"><?=$title?></h4><?
                            }?>
                            <? $phone = $item['phone'];
                            $worktime = $item['worktime'];
                            if($phone || $worktime) {?>
                                <div class="contact-list">
                                    <ul><?
                                        if($phone){?><li><?_e('Phone:','storefront')?> <a href="tel:<?=preg_replace('/[^0-9]/','',$phone)?>"><?=$phone?></a></li><? }
                                        if($phone){?><li><?_e('Work time:','storefront')?> <span><?=$worktime?></span></li><? }
                                        ?></ul>
                                </div>
                            <? }?>
                        </div>
                        <? endforeach;
                        endif;
                        if(!empty($tab['blocks'])):
                            $blocks=$tab["blocks"];
                            foreach($blocks as $item):
                        $title = $item['title']?$item['title']:''; $text = $item['text'];
                        ?>
                        <div class="rs-client-info__item">
                            <?if(!empty($title)):?>
                            <h4 class="s-bold-title"><?=$title?></h4>
                            <?endif; ?>
                            <div class="rs-client-info__part">
                                <?=$text?>
                            </div>
                        </div>
                        <? endforeach;
                        endif;
                        if(!empty($tab['pereklyuchatel'])):
                            $size=$tab["pereklyuchatel"];?>
                        <div class="rs-client-info__size">
                            <div data-tabs class="rs-client-info__size_tabs tabs">
                                <nav data-tabs-titles class="tabs__navigation">
								<?foreach($size as $key=>$item):?>
									<button type="button" data-id="tabs__title_<?=$key?>" class="tabs__title <?=$key==0?'_tab-active':''?>"><?=$item['name']?></button>
								<? endforeach;?>
                                </nav>
                                <div data-tabs-body class="tabs__content">
                            <?foreach($size as $key=>$item):?>
                                <div class="tabs__body tabs__title_<?=$key?>">
                                    <div class="rs-client-info__block">
                                        <div class="rs-client-info__size_table">
                                            <?=$item['main_text']?>
                                        </div>
                                        <?if(!empty($item['blosk_img'])):
                                            $blosk_img=$item['blosk_img'];
                                            ?>
                                        <div class="rs-client-info__size_tippy tippy">
                                            <div class="tippy__img">
                                                <img src="<?=$blosk_img['img']?>" alt="">
                                            </div>
                                             <?if(!empty($blosk_img['hover']) ):
                                                 $hover=$blosk_img['hover'];
                                                 ?>
                                            <div class="tippy__list">
                                                <?foreach($hover as $key => $item):?>
                                                <div class="tippy__item <?=$key==0?'_active-tippy':''?> tippy__item-<?=(int)$key+1?>" style="top:<?=$item['top']?>;left:<?=$item['left']?>;bottom:<?=$item['bottom']?>;right:<?=$item['right']?>;">
                                                    <div class="tippy__number"><?=(int)$key+1?></div>
                                                    <div class="tippy__text"><?=$item['text']?></div>
                                                </div>
                                                <?endforeach;?>
                                            </div>
                                             <?endif;?>
                                        </div>
                                        <?endif;?>
                                    </div>
                                </div>
                            <? endforeach;?>
                                </div>
                            </div>
                        </div>
                       <? endif;
                        if(is_array($tab["header"]) && !$tab["header"][0]['top'] && !empty($tab["header"]) ):
                            $header=$tab["header"];
                            foreach($header as $item): ?>
                                <div class="rs-client-info__item">
                                    <? $title = $item['title'];
                                    if($title) {
                                        ?><h4 class="s-bold-title"><?=$title?></h4><?
                                    }?>
                                    <? $phone = $item['phone'];
                                    $worktime = $item['worktime'];
                                    if($phone || $worktime) {?>
                                        <div class="contact-list">
                                            <ul><?
                                                if($phone){?><li><?_e('Phone:','storefront')?> <a href="tel:<?=preg_replace('/[^0-9]/','',$phone)?>"><?=$phone?></a></li><? }
                                                if($phone){?><li><?_e('Work time:','storefront')?> <span><?=$worktime?></span></li><? }
                                                ?></ul>
                                        </div>
                                    <? }?>
                                </div>
                            <? endforeach;
                        endif;
                        ?>
                    </div>
                    <?endforeach;
				endif;
				?>
					<div class="tabs__body" hidden>
						<h2 class="section-title"><?=get_field('title_privacy_policy')?></h2>
						<? $text = get_field('content_privacy_policy'); if($text) { ?>
						<div class="rs-client-info__item">
							<div class="rs-client-info__part">
								<?=$text?>
							</div>
						</div>
						<? } ?>
					</div>
					<div class="tabs__body" hidden>
						<h2 class="section-title"><?=get_field('title_terms_of_use')?></h2>
						<? $text = get_field('content_terms_of_use'); if($text) { ?>
						<div class="rs-client-info__item">
							<div class="rs-client-info__part">
								<?=$text?>
							</div>
						</div>
						<? } ?>
					</div>
				</div>
				<!-- rs-form -->
				<section class="rs-form">
					<div class="rs-form__container">
						<h2 class="section-title"><?=get_field('title_form')?></h2>
						<div class="rs-form__wrapper">
							<form class="form" id="rs_clients_form" data-page="<?=get_the_ID()?>">
								<div class="form-wrapper">
									<div class="form-block">
										<div class="form-field">
											<textarea autocomplete="off" name="user-message"
												placeholder="<?_e('Ask a question or write your wishes and suggestions','storefront')?>"
												class="rs-input"></textarea>
										</div>
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
</section>
<!-- /rs-client-info -->
<?php
get_footer();