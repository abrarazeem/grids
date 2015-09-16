<?php
namespace Presentation\Grids\Component;

use Nayjest\Tree\NodeCollection;
use Presentation\Grids\Grid;

trait InitializableTrait
{

    private $initialized = false;

    abstract protected function initializeInternal(Grid $grid);

    /** @var Grid */
    protected $grid;

    /**
     * @return NodeCollection
     */
    abstract protected function children();

    final public function initialize(Grid $grid)
    {
        if ($this->initialized) {
            return;
        }
        $this->initializeInternal($grid);
        $this->grid = $grid;
        $this->enableDeferredInitialization($grid);
        $this->initialized = true;
    }

    final protected function enableDeferredInitialization(Grid $grid)
    {
        $collection = $this->children();
        if ($collection->isWritable()) {
            $collection->onItemAdd(function($item) use($grid) {
                if ($item instanceof InitializableInterface) {
                    $item->initialize($grid);
                }
            });
        }
    }

    final public function isInitialized()
    {
        return $this->initialized;
    }
}
