<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

function ach_admin_page() {
    // Get saved achievements
    $achievements = get_option('ach_achievements', array());
    $categories = get_option('ach_categories', array('General'));
    ?>
    <div class="wrap ach-admin-wrap">
        <h1><?php echo esc_html__('Achievements Manager', 'achievements-manager'); ?></h1>
        
        <div class="ach-admin-container">
            <div class="ach-admin-header">
                <h2><?php echo esc_html__('Manage Achievements', 'achievements-manager'); ?></h2>
                <p><?php echo esc_html__('Add, edit, or remove achievements. Use the shortcode generator to create a custom shortcode for your site.', 'achievements-manager'); ?></p>
            </div>
            
            <div class="ach-admin-content">
                <div class="ach-achievements-list">
                    <div class="ach-list-header">
                        <h3><?php echo esc_html__('Your Achievements', 'achievements-manager'); ?></h3>
                        <button type="button" class="button button-primary ach-add-new"><?php echo esc_html__('Add New Achievement', 'achievements-manager'); ?></button>
                    </div>
                    
                    <div class="ach-items-container">
                        <?php if (empty($achievements)) : ?>
                            <div class="ach-empty-state">
                                <p><?php echo esc_html__('No achievements found. Click "Add New Achievement" to create your first one.', 'achievements-manager'); ?></p>
                            </div>
                        <?php else : ?>
                            <div class="ach-items">
                                <?php foreach ($achievements as $index => $achievement) : ?>
                                    <div class="ach-item" data-id="<?php echo esc_attr($achievement['id']); ?>">
                                        <div class="ach-item-header">
                                            <span class="ach-item-icon"><i class="dashicons <?php echo esc_attr($achievement['icon']); ?>"></i></span>
                                            <div class="ach-item-details">
                                                <span class="ach-item-name"><?php echo esc_html($achievement['name']); ?></span>
                                                <span class="ach-item-meta">
                                                    <span class="ach-item-category"><?php echo esc_html($achievement['category']); ?></span>
                                                    <span class="ach-item-date"><?php echo isset($achievement['date']) ? esc_html(date('M d, Y', strtotime($achievement['date']))) : ''; ?></span>
                                                </span>
                                            </div>
                                            <div class="ach-item-actions">
                                                <button type="button" class="button ach-edit-item"><?php echo esc_html__('Edit', 'achievements-manager'); ?></button>
                                                <button type="button" class="button ach-delete-item"><?php echo esc_html__('Delete', 'achievements-manager'); ?></button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="ach-form-container" style="display: none;">
                    <div class="ach-form">
                        <h3 class="ach-form-title"><?php echo esc_html__('Add New Achievement', 'achievements-manager'); ?></h3>
                        
                        <form id="ach-achievement-form">
                            <input type="hidden" id="ach-item-id" value="">
                            
                            <div class="ach-form-field">
                                <label for="ach-name"><?php echo esc_html__('Achievement Name', 'achievements-manager'); ?></label>
                                <input type="text" id="ach-name" name="ach-name" required>
                            </div>
                            
                            <div class="ach-form-field">
                                <label for="ach-category"><?php echo esc_html__('Category', 'achievements-manager'); ?></label>
                                <div class="ach-category-selector">
                                    <input type="text" id="ach-category" name="ach-category" required>
                                    <div class="ach-category-suggestions">
                                        <?php foreach ($categories as $category) : ?>
                                            <div class="ach-category-option" data-category="<?php echo esc_attr($category); ?>"><?php echo esc_html($category); ?></div>
                                        <?php endforeach; ?>
                                        <div class="ach-category-option ach-add-category" data-category="new">+ Add New Category</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="ach-form-field">
                                <label for="ach-date"><?php echo esc_html__('Achievement Date', 'achievements-manager'); ?></label>
                                <input type="text" id="ach-date" name="ach-date" class="ach-datepicker" required>
                            </div>
                            
                            <div class="ach-form-field">
                                <label><?php echo esc_html__('Select Icon', 'achievements-manager'); ?></label>
                                <div class="ach-icon-selector">
                                    <div class="ach-selected-icon">
                                        <i class="dashicons dashicons-awards"></i>
                                        <span><?php echo esc_html__('Selected Icon', 'achievements-manager'); ?></span>
                                    </div>
                                    <button type="button" class="button ach-toggle-icons"><?php echo esc_html__('Choose Icon', 'achievements-manager'); ?></button>
                                    <input type="hidden" id="ach-icon" name="ach-icon" value="dashicons-awards">
                                    
                                    <div class="ach-icons-grid">
                                        <div class="ach-icon-option" data-icon="dashicons-awards"><i class="dashicons dashicons-awards"></i></div>
                                        <div class="ach-icon-option" data-icon="dashicons-star-filled"><i class="dashicons dashicons-star-filled"></i></div>
                                        <div class="ach-icon-option" data-icon="dashicons-yes"><i class="dashicons dashicons-yes"></i></div>
                                        <div class="ach-icon-option" data-icon="dashicons-businessman"><i class="dashicons dashicons-businessman"></i></div>
                                        <div class="ach-icon-option" data-icon="dashicons-chart-bar"><i class="dashicons dashicons-chart-bar"></i></div>
                                        <div class="ach-icon-option" data-icon="dashicons-chart-line"><i class="dashicons dashicons-chart-line"></i></div>
                                        <div class="ach-icon-option" data-icon="dashicons-groups"><i class="dashicons dashicons-groups"></i></div>
                                        <div class="ach-icon-option" data-icon="dashicons-hammer"><i class="dashicons dashicons-hammer"></i></div>
                                        <div class="ach-icon-option" data-icon="dashicons-heart"><i class="dashicons dashicons-heart"></i></div>
                                        <div class="ach-icon-option" data-icon="dashicons-lightbulb"><i class="dashicons dashicons-lightbulb"></i></div>
                                        <div class="ach-icon-option" data-icon="dashicons-money-alt"><i class="dashicons dashicons-money-alt"></i></div>
                                        <div class="ach-icon-option" data-icon="dashicons-performance"><i class="dashicons dashicons-performance"></i></div>
                                        <div class="ach-icon-option" data-icon="dashicons-shield"><i class="dashicons dashicons-shield"></i></div>
                                        <div class="ach-icon-option" data-icon="dashicons-thumbs-up"><i class="dashicons dashicons-thumbs-up"></i></div>
                                        <div class="ach-icon-option" data-icon="dashicons-trophy"><i class="dashicons dashicons-trophy"></i></div>
                                        <div class="ach-icon-option" data-icon="dashicons-universal-access"><i class="dashicons dashicons-universal-access"></i></div>
                                        <div class="ach-icon-option" data-icon="dashicons-superhero"><i class="dashicons dashicons-superhero"></i></div>
                                        <div class="ach-icon-option" data-icon="dashicons-flag"><i class="dashicons dashicons-flag"></i></div>
                                        <div class="ach-icon-option" data-icon="dashicons-smiley"><i class="dashicons dashicons-smiley"></i></div>
                                        <div class="ach-icon-option" data-icon="dashicons-admin-site"><i class="dashicons dashicons-admin-site"></i></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="ach-form-actions">
                                <button type="button" class="button ach-cancel-form"><?php echo esc_html__('Cancel', 'achievements-manager'); ?></button>
                                <button type="submit" class="button button-primary ach-save-item"><?php echo esc_html__('Save Achievement', 'achievements-manager'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="ach-shortcode-generator">
                <h3><?php echo esc_html__('Shortcode Generator', 'achievements-manager'); ?></h3>
                <p><?php echo esc_html__('Create a custom shortcode to display your achievements as a slider.', 'achievements-manager'); ?></p>
                
                <div class="ach-shortcode-options">
                    <div class="ach-shortcode-field">
                        <label for="ach-shortcode-category"><?php echo esc_html__('Filter by Category', 'achievements-manager'); ?></label>
                        <select id="ach-shortcode-category">
                            <option value=""><?php echo esc_html__('All Categories', 'achievements-manager'); ?></option>
                            <?php foreach ($categories as $category) : ?>
                                <option value="<?php echo esc_attr($category); ?>"><?php echo esc_html($category); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="ach-shortcode-field">
                        <label for="ach-shortcode-limit"><?php echo esc_html__('Number of Achievements', 'achievements-manager'); ?></label>
                        <input type="number" id="ach-shortcode-limit" min="-1" value="-1">
                        <span class="ach-field-hint"><?php echo esc_html__('Enter -1 to show all', 'achievements-manager'); ?></span>
                    </div>
                </div>
                
                <div class="ach-generated-shortcode">
                    <label><?php echo esc_html__('Your Shortcode', 'achievements-manager'); ?></label>
                    <input type="text" id="ach-shortcode-result" readonly value='[display_achievements]'>
                    <button type="button" class="button ach-copy-shortcode"><?php echo esc_html__('Copy', 'achievements-manager'); ?></button>
                </div>
            </div>
            
            <div class="ach-admin-footer">
                <div class="ach-save-container">
                    <button type="button" class="button button-primary ach-save-all"><?php echo esc_html__('Save All Changes', 'achievements-manager'); ?></button>
                    <span class="ach-save-status"></span>
                </div>
                <div class="ach-plugin-credit">
                    <p>Created by <a href="https://perfviz.com" target="_blank">Perfviz</a> - perfviz.com</p>
                </div>
            </div>
        </div>
    </div>
    <?php
}
