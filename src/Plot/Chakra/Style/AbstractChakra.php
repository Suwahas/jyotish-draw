<?php
/**
 * @link      http://github.com/kunjara/jyotish for the canonical source repository
 * @license   GNU General Public License version 2 or later
 */

namespace Jyotish\Draw\Plot\Chakra\Style;

use Jyotish\Base\Analysis;
use Jyotish\Ganita\Matrix;

/**
 * Class for generate Chakra.
 *
 * @author Kunjara Lila das <vladya108@gmail.com>
 */
abstract class AbstractChakra
{
    use \Jyotish\Base\Traits\DataTrait;
    
    /**
     * Triangle bhava
     */
    const BHAVA_TRIANGLE = 'triangle';
    /**
     * Rectangle bhava
     */
    const BHAVA_RECTANGLE = 'rectangle';
    
    /**
     * North Indian style
     */
    const STYLE_NORTH = 'north';
    /**
     * South Indian style
     */
    const STYLE_SOUTH = 'south';
    /**
     * Eastern Indian Style
     */
    const STYLE_EAST = 'east';
    
    const COUNT_ONE = 'one';
    const COUNT_FOUR = 'four';
    const COUNT_FIVE = 'five';
    const COUNT_MORE = 'more';

    /**
     * List of styles.
     * 
     * @var array
     */
    public static $style = [
        self::STYLE_NORTH,
        self::STYLE_SOUTH,
        self::STYLE_EAST,
    ];
    
    /**
     * Analysis object.
     * 
     * @var \Jyotish\Base\Analysis
     */
    protected $Analysis = null;
    
    /**
     * Chakra graha.
     * 
     * @var string
     */
    protected $chakraGraha;
    
    /**
     * Chakra divider.
     * 
     * @var int
     */
    protected $chakraDivider;
    
    /**
     * Base coordinates of bhavas.
     * 
     * @var array
     */
    protected $bhavaPointsBase = [];
    
    /**
     * Base coordinates of grahas.
     * 
     * @var array
     */
    protected $grahaPointsBase = [];

    /**
     * Rules of transformations.
     * 
     * @var array
     */
    protected $transformRules = [];
    
    /**
     * Bhava coordinates after transformations.
     * 
     * @var array
     */
    protected $bhavaPoints = [];

    /**
     * Constructor
     * 
     * @param \Jyotish\Base\Data $Data
     */
    public function __construct(\Jyotish\Base\Data $Data)
    {
        $this->setData($Data);
        
        $this->Analysis = new Analysis($Data);
    }

    /**
     * Get bhava points.
     * 
     * @param int $leftOffset Left offset
     * @param int $topOffset Top offset
     * @param array $options
     * @return array
     */
    public function getBhavaPoints($leftOffset = 0, $topOffset = 0, array $options = null)
    {
        $myPoints = [];
        foreach ($this->transformRules as $bhavaKey => $transformInfo) {
            $bhavaPoints = $this->bhavaPointsBase[$transformInfo['base']];
            foreach ($bhavaPoints as $point => $value) {
                if ($point % 2) {
                    $y = $value;
                    $factor = round($options['chakraSize'] / $this->chakraDivider);
                    $transformInfo['transform'][Matrix::TYPE_SCALING] = [$factor, $factor];
                    $matrixCoord = Matrix::getInstance(Matrix::TYPE_DEFAULT, [[$x, $y, 1]]);
                    foreach ($transformInfo['transform'] as $transform => $params) {
                        $matrixTransform = Matrix::getInstance($transform, ...$params);
                        $matrixCoord->multiMatrix($matrixTransform);
                    }
                    $arrayCoord = $matrixCoord->toArray();
                    list($x, $y) = $arrayCoord[0];
                    
                    $myPoints[$bhavaKey][] = $x + $leftOffset;
                    $myPoints[$bhavaKey][] = $y + $topOffset;
                } else {
                    $x = $value;
                }
            }
        }
        $this->bhavaPoints = $myPoints;
        
        return $myPoints;
    }

    /**
     * Get rashi label points.
     */
    abstract public function getRashiLabelPoints(array $options);

    /**
     * Get body label points.
     */
    abstract public function getBodyLabelPoints($leftOffset = 0, $topOffset = 0, array $options = null);
}