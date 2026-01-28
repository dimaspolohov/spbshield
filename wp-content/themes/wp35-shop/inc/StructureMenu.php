<?php
/**
 * Structure Menu Helper
 * 
 * Helps to build structured menu with levels
 * 
 * @package SpbShield
 * @since 1.0.0
 */

namespace SpbShield\Inc;

class StructureMenu {
    
    /**
     * Menu name or ID
     * 
     * @var string|int
     */
    private string|int $menu_name;
    
    /**
     * Unsorted menu items
     * 
     * @var array
     */
    private array $menu_items = [];
    
    /**
     * Sorted menu items
     * 
     * @var array
     */
    private array $menu_struct_items = [];
    
    /**
     * Constructor
     * 
     * @param string|int $menu_name Menu name or ID
     */
    public function __construct(string|int $menu_name) {
        $this->menu_name = $menu_name;
    }
    
    /**
     * Get structured menu items
     * 
     * @return array Structured menu items
     */
    public function getItemsStructureMenu(): array {
        if (empty($this->menu_name)) {
            return [];
        }
        
        // Get menu items
        $this->menu_items = wp_get_nav_menu_items($this->menu_name);
        
        if (!$this->menu_items) {
            return [];
        }
        
        $this->menu_struct_items = $this->sort_structure_menu();
        
        return $this->menu_struct_items;
    }
    
    /**
     * Sort menu structure by levels
     * 
     * @return array Sorted menu structure
     */
    private function sort_structure_menu(): array {
        if (empty($this->menu_items)) {
            return [];
        }
        
        $result = [];
        $current_level = 1;
        $last_level_item_menu = [];
        
        foreach ($this->menu_items as &$menu_item) {
            // Determine level
            if ($menu_item->menu_item_parent == 0) {
                $current_level = 1;
            } else {
                if (isset($last_level_item_menu[$current_level]) && 
                    $last_level_item_menu[$current_level]->ID == $menu_item->menu_item_parent) {
                    // Previous item was our parent - increase level
                    $current_level++;
                } elseif (isset($last_level_item_menu[$current_level - 1]) && 
                          $last_level_item_menu[$current_level - 1]->ID == $menu_item->menu_item_parent) {
                    // Same parent as previous - same level
                } else {
                    // Level decreased - find parent in all levels
                    foreach ($last_level_item_menu as $level => $item_menu) {
                        if ($item_menu->ID == $menu_item->menu_item_parent) {
                            $current_level = $level + 1;
                            break;
                        }
                    }
                }
            }
            
            // Store reference to current level item
            $last_level_item_menu[$current_level] = &$menu_item;
            
            // Add level property
            $last_level_item_menu[$current_level]->level_menu = $current_level;
            
            if ($current_level === 1) {
                // First level - add to result
                $result[] = $menu_item;
            } else {
                // Child level - add to parent's sub_item_menu
                if (!isset($last_level_item_menu[$current_level - 1]->sub_item_menu)) {
                    $last_level_item_menu[$current_level - 1]->sub_item_menu = [];
                }
                
                $last_level_item_menu[$current_level - 1]->sub_item_menu[] = $last_level_item_menu[$current_level];
            }
        }
        
        return $result;
    }
}
