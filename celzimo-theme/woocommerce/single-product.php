<?php
/**
 * The Template for displaying all single products
 *
 * @package Celzimo_Veste
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

get_header();
?>

<main class="product-page-main container" id="product-container" style="padding-top: 40px; padding-bottom: 80px;">
    <?php
    while ( have_posts() ) :
        the_post();
        global $product;
        ?>
        <div class="product-page-layout">
            <!-- Left Column: Gallery -->
            <div class="product-page-image" style="display: flex; gap: 20px; align-items: flex-start; width: 100%;">
                <?php
                $main_img_url = has_post_thumbnail() ? wp_get_attachment_image_url( $product->get_image_id(), 'full' ) : get_template_directory_uri() . '/assets/placeholder.png';
                $attachment_ids = $product->get_gallery_image_ids();
                ?>
                
                <?php if ( !empty( $attachment_ids ) ) : ?>
                <!-- Left: Thumbnails (Vertical) -->
                <div class="product-gallery-vertical" style="display: flex; flex-direction: column; gap: 10px; width: 80px; max-height: 550px; overflow-y: auto; padding-right: 5px; flex-shrink: 0;">
                    <img src="<?php echo esc_url( $main_img_url ); ?>" class="gallery-thumb active" data-index="0" alt="Vista Principal" onclick="selectProductGalleryImg(event, this, '<?php echo esc_url( $main_img_url ); ?>')" style="width: 100%; height: 90px; object-fit: cover; border: 1px solid #e5e7eb; cursor: pointer; transition: all 0.2s; border-radius: 4px;">
                    <?php
                    $index = 1;
                    foreach ( $attachment_ids as $attachment_id ) {
                        $img_url = wp_get_attachment_image_url( $attachment_id, 'full' );
                        echo '<img src="' . esc_url( $img_url ) . '" class="gallery-thumb" data-index="' . $index . '" alt="Vista Detalle" onclick="selectProductGalleryImg(event, this, \'' . esc_url( $img_url ) . '\')" style="width: 100%; height: 90px; object-fit: cover; border: 1px solid #e5e7eb; cursor: pointer; transition: all 0.2s; border-radius: 4px;">';
                        $index++;
                    }
                    ?>
                </div>
                <?php endif; ?>

                <!-- Right: Main Image with Arrows & Accordions below -->
                <div class="product-main-image-container" style="position: relative; flex: 1; display: flex; flex-direction: column;">
                    <div style="position: relative; border-radius: 4px; overflow: hidden; background: #f9fafb;">
                        <img id="product-page-main-img" src="<?php echo esc_url( $main_img_url ); ?>" alt="<?php the_title(); ?>" style="width: 100%; object-fit: cover; max-height: 550px; display: block;">
                        
                        <?php if ( !empty( $attachment_ids ) ) : ?>
                        <button class="gallery-arrow gallery-prev" aria-label="Anterior" style="position: absolute; top: 50%; left: 15px; transform: translateY(-50%); background: rgba(255,255,255,0.9); border: 1px solid #e5e7eb; border-radius: 50%; width: 40px; height: 40px; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 6px rgba(0,0,0,0.05); color: #333; transition: all 0.2s; z-index: 10;"><i class="ti ti-chevron-left" style="font-size: 1.2rem;"></i></button>
                        <button class="gallery-arrow gallery-next" aria-label="Siguiente" style="position: absolute; top: 50%; right: 15px; transform: translateY(-50%); background: rgba(255,255,255,0.9); border: 1px solid #e5e7eb; border-radius: 50%; width: 40px; height: 40px; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 6px rgba(0,0,0,0.05); color: #333; transition: all 0.2s; z-index: 10;"><i class="ti ti-chevron-right" style="font-size: 1.2rem;"></i></button>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Accordions below main image -->
                    <div class="product-accordions" style="margin-top: 20px;">
                        <!-- Accordion 1: Información adicional -->
                        <div class="accordion-item" style="background: #f9fafb; border-radius: 6px; margin-bottom: 6px; overflow: hidden;">
                            <button class="accordion-btn" style="width: 100%; display: flex; justify-content: space-between; align-items: center; padding: 10px 14px; background: none; border: none; font-size: 0.85rem; font-weight: 600; cursor: pointer; color: #1a1a2e;">
                                <span>Información adicional</span>
                                <i class="ti ti-chevron-down" style="transition: transform 0.3s; color: #9ca3af; font-size: 0.9rem;"></i>
                            </button>
                            <div class="accordion-content" style="max-height: 0; overflow: hidden; transition: max-height 0.3s ease-out; font-size: 0.8rem; line-height: 1.4; color: var(--color-text-light);">
                                <div style="padding: 0 14px 12px;">
                                    <?php 
                                    // You can output extra description here or leave it empty/placeholder
                                    echo '<p>Encuentra más detalles sobre la composición y el cuidado de esta prenda.</p>';
                                    ?>
                                </div>
                            </div>
                        </div>

                        <!-- Accordion 2: Especificaciones -->
                        <div class="accordion-item" style="background: #f9fafb; border-radius: 6px; margin-bottom: 6px; overflow: hidden;">
                            <button class="accordion-btn" style="width: 100%; display: flex; justify-content: space-between; align-items: center; padding: 10px 14px; background: none; border: none; font-size: 0.85rem; font-weight: 600; cursor: pointer; color: #1a1a2e;">
                                <span>Especificaciones</span>
                                <i class="ti ti-chevron-down" style="transition: transform 0.3s; color: #9ca3af; font-size: 0.9rem;"></i>
                            </button>
                            <div class="accordion-content" style="max-height: 0; overflow: hidden; transition: max-height 0.3s ease-out; font-size: 0.8rem; line-height: 1.4; color: var(--color-text-light);">
                                <div style="padding: 0 14px 12px;">
                                    <?php 
                                    if ( $product->has_attributes() ) {
                                        wc_display_product_attributes( $product );
                                    } else {
                                        echo '<p>Sin especificaciones adicionales.</p>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <!-- Accordion 3: Devolver este producto es gratis -->
                        <div class="accordion-item" style="background: #f9fafb; border-radius: 6px; margin-bottom: 6px; overflow: hidden;">
                            <button class="accordion-btn" style="width: 100%; display: flex; justify-content: space-between; align-items: center; padding: 10px 14px; background: none; border: none; font-size: 0.85rem; font-weight: 600; cursor: pointer; color: #1a1a2e;">
                                <span>Devolver este producto es gratis</span>
                                <i class="ti ti-chevron-down" style="transition: transform 0.3s; color: #9ca3af; font-size: 0.9rem;"></i>
                            </button>
                            <div class="accordion-content" style="max-height: 0; overflow: hidden; transition: max-height 0.3s ease-out; font-size: 0.8rem; line-height: 1.4; color: var(--color-text-light);">
                                <div style="padding: 0 14px 12px;">
                                    <p>Realizamos envíos a todo Chile continental. Los tiempos de despacho varían entre 2 a 5 días hábiles. Tienes hasta 30 días para realizar cambios de talla gratis en Santiago.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Info & Form -->
            <div class="product-page-info">
                <div class="breadcrumbs" style="font-size: 0.8rem; color: #9ca3af; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 0.5px;">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="color: #9ca3af; text-decoration: none; transition: color 0.2s;">Inicio</a> <span style="margin: 0 4px;">/</span> 
                    <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" style="color: #9ca3af; text-decoration: none; transition: color 0.2s;">Catálogo</a> <span style="margin: 0 4px;">/</span> 
                    <span style="color: #1a1a2e; font-weight: 500;"><?php the_title(); ?></span>
                </div>
                
                <div class="brand" style="font-size: 0.85rem; color: #6b7280; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 8px; font-weight: 600;">
                    <?php echo esc_html( $product->get_attribute( 'pa_brand' ) ? $product->get_attribute( 'pa_brand' ) : 'Carola Miccono' ); ?>
                </div>
                
                <h1 class="title" style="font-size: 2.2rem; font-family: var(--font-secondary); color: #111827; margin-bottom: 8px; font-weight: 700; line-height: 1.2; letter-spacing: -0.5px;">
                    <?php the_title(); ?>
                </h1>
                
                <div class="sku" style="font-size: 0.75rem; color: #9ca3af; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 1px;">
                    SKU: <?php echo esc_html( $product->get_sku() ); ?>
                </div>
                
                <div class="price-container" style="margin-bottom: 25px;">
                    <?php 
                    $price_html = $product->get_price_html();
                    $discount = 0;
                    
                    if ( $product->is_on_sale() ) {
                        if ( $product->is_type('variable') ) {
                            $available_variations = $product->get_available_variations();
                            $max_percentage = 0;
                            foreach( $available_variations as $variation ) {
                                $regular_price = (float) $variation['display_regular_price'];
                                $sale_price = (float) $variation['display_price'];
                                if ( $regular_price > 0 && $regular_price > $sale_price ) {
                                    $percentage = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
                                    if ( $percentage > $max_percentage ) {
                                        $max_percentage = $percentage;
                                    }
                                }
                            }
                            $discount = $max_percentage;
                        } else {
                            $display_price = (float) $product->get_price();
                            $display_regular_price = (float) $product->get_regular_price();
                            if ($display_regular_price > 0) {
                                $discount = round( ( ( $display_regular_price - $display_price ) / $display_regular_price ) * 100 );
                            }
                        }
                        
                        if ($discount > 0) {
                            if (strpos($price_html, '</ins>') !== false) {
                                $price_html = str_replace('</ins>', ' <span class="discount-badge">-' . $discount . '%</span></ins>', $price_html);
                            } else {
                                $price_html .= ' <span class="discount-badge">-' . $discount . '%</span>';
                            }
                        }
                    }
                    
                    echo '<div class="price">' . $price_html . '</div>'; 
                    ?>
                </div>
                
                <div class="description" style="font-size: 0.95rem; color: #4b5563; line-height: 1.8; margin-bottom: 35px; text-align: justify;">
                    <?php the_content(); ?>
                </div>

                <!-- WooCommerce Add to Cart Form -->
                <div class="woo-add-to-cart-wrapper" style="margin-bottom: 35px;">
                    <?php woocommerce_template_single_add_to_cart(); ?>
                </div>
            </div>
        </div>
        <?php
    endwhile;
    ?>
</main>

<!-- Script to handle gallery thumbnail clicks and Accordion toggle -->
<script>
let currentGalleryIndex = 0;

function selectProductGalleryImg(event, element, newSrc) {
    event.preventDefault();
    event.stopPropagation();
    
    // Update active class on gallery thumbnails
    const container = element.closest('.product-gallery-vertical');
    if (container) {
        container.querySelectorAll('.gallery-thumb').forEach(thumb => thumb.classList.remove('active'));
        element.classList.add('active');
        if (element.hasAttribute('data-index')) {
            currentGalleryIndex = parseInt(element.getAttribute('data-index'), 10);
        }
    }
    
    const mainImg = document.getElementById('product-page-main-img');
    if (mainImg) {
        mainImg.src = newSrc;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    // Accordion logic
    const accordionBtns = document.querySelectorAll('.accordion-btn');
    accordionBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            this.classList.toggle('active');
            const icon = this.querySelector('i');
            const content = this.nextElementSibling;
            
            if (this.classList.contains('active')) {
                content.style.maxHeight = content.scrollHeight + "px";
                if (icon) icon.style.transform = 'rotate(180deg)';
            } else {
                content.style.maxHeight = "0";
                if (icon) icon.style.transform = 'rotate(0deg)';
            }
        });
    });

    // Gallery Arrows logic
    const thumbs = document.querySelectorAll('.gallery-thumb');
    const prevBtn = document.querySelector('.gallery-prev');
    const nextBtn = document.querySelector('.gallery-next');
    
    if (prevBtn && nextBtn && thumbs.length > 0) {
        prevBtn.addEventListener('click', (e) => {
            e.preventDefault();
            currentGalleryIndex = (currentGalleryIndex - 1 + thumbs.length) % thumbs.length;
            thumbs[currentGalleryIndex].click();
        });
        
        nextBtn.addEventListener('click', (e) => {
            e.preventDefault();
            currentGalleryIndex = (currentGalleryIndex + 1) % thumbs.length;
            thumbs[currentGalleryIndex].click();
        });
    }
});

jQuery(document).ready(function($) {
    // 0. Handle Variation Price Badge
    $(document).on('show_variation', 'form.variations_form', function(event, variation) {
        if (variation.display_price < variation.display_regular_price) {
            let discount = Math.round(((variation.display_regular_price - variation.display_price) / variation.display_regular_price) * 100);
            setTimeout(function() {
                let $priceContainer = $('.woocommerce-variation-price .price');
                let $ins = $priceContainer.find('ins');
                if ($ins.length && !$ins.find('.discount-badge').length) {
                    $ins.append(' <span class="discount-badge">-' + discount + '%</span>');
                } else if ($priceContainer.length && !$ins.length && !$priceContainer.find('.discount-badge').length) {
                    $priceContainer.append(' <span class="discount-badge">-' + discount + '%</span>');
                }
            }, 10);
        }
    });

    // 1. Swatches Logic
    const $form = $('form.variations_form');
    if ($form.length > 0) {
        const variationsData = $form.data('product_variations');
        
        $('table.variations select').each(function() {
            const $select = $(this);
            const attributeName = $select.attr('name');
            // Check if attribute is "color" or similar
            const isColor = attributeName.toLowerCase().indexOf('color') !== -1;
            
            const $wrapper = $('<div class="custom-swatches-wrapper"></div>');
            $select.before($wrapper);
            
            const $label = $select.closest('tr').find('.label label');
            const originalLabelText = $label.text().replace(':', '').trim();
            $label.hide();
            
            const $customLabel = $('<div class="swatch-label"></div>');
            if (isColor) {
                 $customLabel.html('<strong>' + originalLabelText + ':</strong> <span class="selected-val"></span>');
            } else {
                 $customLabel.html('<strong>Elige una opci&oacute;n:</strong>');
            }
            $wrapper.append($customLabel);
            
            const $swatches = $('<div class="swatches-container"></div>');
            $wrapper.append($swatches);
            
            $select.find('option').each(function() {
                const val = $(this).attr('value');
                if (!val) return; // Skip default empty option
                const text = $(this).text();
                
                let $swatch;
                if (isColor) {
                    let thumbSrc = '';
                    if (variationsData) {
                        const match = variationsData.find(v => v.attributes[attributeName] === val);
                        if (match && match.image && match.image.thumb_src) {
                            thumbSrc = match.image.thumb_src;
                        }
                    }
                    if (thumbSrc) {
                        $swatch = $('<div class="swatch color-swatch" data-value="'+val+'" title="'+text+'"><img src="'+thumbSrc+'" alt="'+text+'"></div>');
                    } else {
                        $swatch = $('<div class="swatch text-swatch" data-value="'+val+'">'+text+'</div>');
                    }
                } else {
                    $swatch = $('<div class="swatch text-swatch" data-value="'+val+'">'+text+'</div>');
                }
                
                $swatches.append($swatch);
                
                $swatch.on('click', function() {
                    if ($(this).hasClass('disabled')) return;
                    $select.val(val).trigger('change');
                });
            });
            
            // Listen to select change to update active state
            $select.on('change', function() {
                const val = $(this).val();
                $swatches.find('.swatch').removeClass('active');
                if (val) {
                    const $activeSwatch = $swatches.find('.swatch[data-value="' + val + '"]');
                    $activeSwatch.addClass('active');
                    if (isColor) {
                        $customLabel.find('.selected-val').text($activeSwatch.text() || val);
                    }
                } else {
                    if (isColor) {
                        $customLabel.find('.selected-val').text('');
                    }
                }
            });
        });

        // WooCommerce events: update available/disabled swatches
        $form.on('woocommerce_update_variation_values', function() {
            $('table.variations select').each(function() {
                const $select = $(this);
                const $swatches = $select.prev('.custom-swatches-wrapper').find('.swatch');
                $swatches.each(function() {
                    const val = $(this).data('value');
                    const $option = $select.find('option[value="' + val + '"]');
                    if ($option.length === 0) {
                        $(this).addClass('disabled');
                    } else {
                        $(this).removeClass('disabled');
                    }
                });
            });
        });
        
        // WooCommerce events: variation selected, inject discount badge
        $form.on('show_variation', function(event, variation) {
            if (variation.display_price < variation.display_regular_price) {
                const discount = Math.round(((variation.display_regular_price - variation.display_price) / variation.display_regular_price) * 100);
                setTimeout(function() {
                    const $ins = $('.woocommerce-variation-price ins');
                    if ($ins.length && $ins.find('.discount-badge').length === 0) {
                        $ins.append('<span class="discount-badge">-' + discount + '%</span>');
                    }
                }, 10);
            }
        });
    }
});
</script>

<style>
/* Swatches Styles */
.custom-swatches-wrapper { margin-bottom: 25px; }
.swatch-label { font-size: 1rem; color: #333; margin-bottom: 12px; }
.swatch-label strong { font-weight: 700; color: #333; }
.swatch-label .selected-val { color: #666; font-weight: 400; margin-left: 4px; text-transform: capitalize;}
.swatches-container { display: flex; flex-wrap: wrap; gap: 10px; }
.swatch { border: 1px solid #e5e7eb; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s; position: relative; overflow: hidden; background: white;}
.swatch.text-swatch { border-radius: 4px; padding: 4px 10px; font-size: 0.8rem; color: #666; font-weight: 600; min-width: 35px;}
.swatch.color-swatch { width: 35px; height: 45px; border-radius: 4px; padding: 2px;}
.swatch.color-swatch img { width: 100%; height: 100%; object-fit: cover; border-radius: 2px;}
.swatch:hover { border-color: #9ca3af; }
.swatch.active { border-color: #1a1a2e; border-width: 2px; color: #1a1a2e;}
.swatch.disabled { opacity: 0.5; cursor: not-allowed; background: #f9fafb; color: #9ca3af; }
.swatch.disabled::after { content: ''; position: absolute; top: 50%; left: 10%; width: 80%; height: 1.5px; background-color: #9ca3af; transform: translateY(-50%) rotate(-25deg); }

/* Hide original elements */
.variations select { display: none !important; }
.variations .label { display: none !important; }
.variations .reset_variations { display: none !important; }
.variations td.value { display: block; width: 100%; padding: 0 !important; }
.variations tr { display: block; margin-bottom: 5px; border-bottom: none !important;}

/* Price Layout Styles */
.woocommerce-variation-price { margin-bottom: 25px; }
.woocommerce-variation-price .price, .price-container .price { display: flex; flex-direction: column; align-items: flex-start;}
.woocommerce-variation-price .price ins, .price-container .price ins {
    text-decoration: none;
    font-size: 2rem;
    font-weight: 600;
    color: #333;
    order: 1;
    display: flex;
    align-items: center;
    gap: 12px;
    background: transparent;
}
.woocommerce-variation-price .price del, .price-container .price del {
    font-size: 1.15rem;
    color: #888;
    order: 2;
    margin-top: 2px;
}
.discount-badge {
    background-color: #e50027;
    color: white;
    font-size: 0.8rem;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 4px;
    display: inline-block;
    line-height: 1;
    vertical-align: middle;
}

/* Cleanup standard price when variation is selected */
.woocommerce-variation-price + .price-container { display: none; }

/* General Layout adjustments */
.gallery-thumb.active {
    border-color: var(--color-accent, #c49a45) !important;
    border-width: 2px !important;
}

/* Add to Cart Area */
.woocommerce-variation-add-to-cart,
form.cart:not(.variations_form) {
    display: flex !important;
    align-items: center;
    gap: 15px;
    flex-wrap: wrap;
}

.quantity {
    margin-bottom: 0 !important;
    display: inline-flex;
    align-items: center;
    flex-shrink: 0;
}
.quantity input {
    width: 60px;
    padding: 10px;
    border: 1px solid #d1d5db;
    border-radius: 4px;
    text-align: center;
    font-size: 1rem;
}

.single_add_to_cart_button {
    width: auto !important;
    flex: 1;
    min-width: 200px;
    max-width: 300px;
    padding: 12px 20px !important;
    background-color: #6c757d !important;
    color: white !important;
    border: 1px solid #6c757d !important;
    border-radius: 4px !important;
    font-size: 0.9rem !important;
    font-weight: 600 !important;
    text-transform: uppercase !important;
    letter-spacing: 1px !important;
    cursor: pointer !important;
    transition: all 0.3s !important;
    margin: 0 !important;
}
.single_add_to_cart_button:hover {
    background-color: #5a6268 !important;
    border-color: #5a6268 !important;
}
</style>


<?php
get_footer();
