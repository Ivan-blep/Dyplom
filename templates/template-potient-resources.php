<?php
/**
 * Template Name: Patient Resources Page
 */
get_header(); ?>

    <section class="patient-hero">
        <div class="patient-hero__bg">
            <img class="" src="<?php echo get_template_directory_uri()?>/assets/images/patient_hero.svg" alt="">
        </div>
        <div class="grid-container  animated-element slide-up">
            <div class="patient-hero__content"><?php echo get_field('patient_hero_content') ?? '' ?></div>
            <div class="patient-hero__button"><?php echo ($patient_hero_button = get_field('patient_hero_button')) ? acf_link($patient_hero_button, 'button') : '' ?></div>
        </div>
    </section>
    <section class="asked">
        <div class="asked--bg"  <?php echo ($bg = get_field('asked_questions_background_image_')) ? bg($bg) : '' ?>></div>
        <div class="grid-container">
            <div class="asked__title  animated-element slide-left">
                <h4><?php echo get_field('asked_questions_title') ?? '' ?></h4>
            </div>
            <div class="asked__container-accordion  animated-element slide-right">
                <?php if ($asked_questions_accordion = get_field('asked_questions_accordion')): ?>
                    <ul class="accordion" data-accordion data-allow-all-closed="true">
                        <?php foreach ($asked_questions_accordion as $item): ?>
                            <li class="accordion-item" data-accordion-item>
                                <a href="#" class="accordion-title"><?php echo $item['title'] ?? '' ?></a>
                                <div class="accordion-content" data-tab-content>
                                    <?php echo $item['content'] ?? '' ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <section class="insurance">
        <div class="insurance__title">
            <div class="grid-container">
                <h1><?php echo get_field('insurance_title') ?? '' ?></h1>
            </div>
        </div>
        <div class="grid-container">
            <?php if ($tab = get_field('insurance_tab')): ?>
                <div class="tab-wrapper">
                    <ul class="vertical tabs" data-tabs id="collapsing-tabs">
                        <?php $n = 0;
                        foreach ($tab as $title) : ?>
                            <li class="tabs-title <?php echo ($n == 0) ? ' is-active' : ''; ?>"><a href="#panel<?php echo $n;  $n++?>"><?php echo $title['title']; ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="tab-content" data-tabs-content="collapsing-tabs">
                        <?php $i = 0;
                        foreach ($tab as $content) : ?>
                            <div id="panel<?php echo $i;?>"
                                    class="tabs-panel <?php echo ($i == 0) ? ' is-active' : ''; ?>">
                                <?php $i++; ?>
                                <?php echo $content['content'] ?? '' ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>


    </section>
<?php get_footer(); ?>