<?php
/**
 * @link      http://github.com/kunjara/jyotish for the canonical source repository
 * @license   GNU General Public License version 2 or later
 */

namespace Jyotish\Draw\Plot\Chakra\Style;

use Jyotish\Graha\Graha;
use Jyotish\Bhava\Bhava;
use Jyotish\Ganita\Matrix;

/**
 * Class for generate South chakra.
 *
 * @author Kunjara Lila das <vladya108@gmail.com>
 */
final class South extends AbstractChakra
{
    /**
     * Chakra graha.
     * 
     * @var string
     */
    protected $chakraGraha = Graha::KEY_GU;
    
    /**
     * Chakra divider.
     * 
     * @var int
     */
    protected $chakraDivider = 4;
    
    /**
     * Base coordinates of bhavas.
     * 
     * @var array
     */
    protected $bhavaPointsBase = [
        self::BHAVA_RECTANGLE => [1, 1,   0, 1,   0, 0,   1, 0],
    ];
    
    /**
     * Base coordinates of grahas.
     * 
     * @var array
     */
    protected $grahaPointsBase = [
        self::BHAVA_RECTANGLE => [
            self::COUNT_ONE  => [0.5, 0.5],
            self::COUNT_FIVE => [0.25, 0.25,   0.75, 0.25,   0.25, 0.75,   0.75, 0.75,   0.5, 0.5],
            self::COUNT_MORE => [
                0.2, 0.2,
                0.5, 0.2,
                0.8, 0.2,
                0.2, 0.8,
                0.5, 0.8,
                0.8, 0.8,
                0.2, 0.5,
                0.8, 0.5,
                0.5, 0.5,
            ]
        ],
    ];
    
    /**
     * Rules of transformations.
     * 
     * @var array
     */
    protected $transformRules = [
        1 => [
            'base' => self::BHAVA_RECTANGLE,
            'transform' => [
                Matrix::TYPE_TRANSLATION => [1, 0],
            ],
        ],
        2 => [
            'base' => self::BHAVA_RECTANGLE,
            'transform' => [
                Matrix::TYPE_TRANSLATION => [2, 0],
            ],
        ],
        3 => [
            'base' => self::BHAVA_RECTANGLE,
            'transform' => [
                Matrix::TYPE_TRANSLATION => [3, 0],
            ],
        ],
        4 => [
            'base' => self::BHAVA_RECTANGLE,
            'transform' => [
                Matrix::TYPE_TRANSLATION => [3, 1],
            ],
        ],
        5 => [
            'base' => self::BHAVA_RECTANGLE,
            'transform' => [
                Matrix::TYPE_TRANSLATION => [3, 2],
            ],
        ],
        6 => [
            'base' => self::BHAVA_RECTANGLE,
            'transform' => [
                Matrix::TYPE_TRANSLATION => [3, 3],
            ],
        ],
        7 => [
            'base' => self::BHAVA_RECTANGLE,
            'transform' => [
                Matrix::TYPE_TRANSLATION => [2, 3],
            ],
        ],
        8 => [
            'base' => self::BHAVA_RECTANGLE,
            'transform' => [
                Matrix::TYPE_TRANSLATION => [1, 3],
            ],
        ],
        9 => [
            'base' => self::BHAVA_RECTANGLE,
            'transform' => [
                Matrix::TYPE_TRANSLATION => [0, 3],
            ],
        ],
        10 => [
            'base' => self::BHAVA_RECTANGLE,
            'transform' => [
                Matrix::TYPE_TRANSLATION => [0, 2],
            ],
        ],
        11 => [
            'base' => self::BHAVA_RECTANGLE,
            'transform' => [
                Matrix::TYPE_TRANSLATION => [0, 1],
            ],
        ],
        12 => [
            'base' => self::BHAVA_RECTANGLE,
            'transform' => [],
        ],
    ];

    /**
     * Get rashi label points.
     * 
     * @param array $options
     * @return array
     */
    public function getRashiLabelPoints(array $options)
    {
        $rashis = $this->Analysis->getRashiInBhava($options['chakraVarga']);

        $myPoints = [];
        foreach ($rashis as $rashi) {
            $bhava = $rashi;

            if ($bhava == 1 || $bhava == 11 || $bhava == 12) {
                $myPoints[$rashi]['x'] = $this->bhavaPoints[$bhava][0] - $options['offsetBorder'];
                $myPoints[$rashi]['y'] = $this->bhavaPoints[$bhava][1] - $options['offsetBorder'];
                $myPoints[$rashi]['textAlign'] = 'right';
                $myPoints[$rashi]['textValign'] = 'bottom';
            } elseif ($bhava == 2 || $bhava == 3 || $bhava == 4) {
                $myPoints[$rashi]['x'] = $this->bhavaPoints[$bhava][2] + $options['offsetBorder'];
                $myPoints[$rashi]['y'] = $this->bhavaPoints[$bhava][3] - $options['offsetBorder'];
                $myPoints[$rashi]['textAlign'] = 'left';
                $myPoints[$rashi]['textValign'] = 'bottom';
            } elseif ($bhava == 5 || $bhava == 6 || $bhava == 7) {
                $myPoints[$rashi]['x'] = $this->bhavaPoints[$bhava][4] + $options['offsetBorder'];
                $myPoints[$rashi]['y'] = $this->bhavaPoints[$bhava][5] + $options['offsetBorder'];
                $myPoints[$rashi]['textAlign'] = 'left';
                $myPoints[$rashi]['textValign'] = 'top';
            } else {
                $myPoints[$rashi]['x'] = $this->bhavaPoints[$bhava][6] - $options['offsetBorder'];
                $myPoints[$rashi]['y'] = $this->bhavaPoints[$bhava][7] + $options['offsetBorder'];
                $myPoints[$rashi]['textAlign'] = 'right';
                $myPoints[$rashi]['textValign'] = 'top';
            }
        }
        return $myPoints;
    }

    /**
     * Get body label points.
     * 
     * @param int $leftOffset Left offset
     * @param int $topOffset Top offset
     * @param array $options
     * @return array
     */
    public function getBodyLabelPoints($leftOffset = 0, $topOffset = 0, array $options = null)
    {
        $bodies = $this->Analysis->getBodyInRashi($options['chakraVarga']);
        $rashiGrahas = [];
        foreach ($bodies as $graha => $rashi) {
            $rashiGrahas[$rashi][] = $graha;
        }

        $myPoints = [];
        foreach ($rashiGrahas as $rashi => $grahas) {
            $bhavaType = self::BHAVA_RECTANGLE;
            $countKey = $this->getCountKey(count($grahas));
            $i = 0;
            foreach ($grahas as $key => $graha) {
                $x = $this->grahaPointsBase[$bhavaType][$countKey][$i * 2];
                $y = $this->grahaPointsBase[$bhavaType][$countKey][$i * 2 + 1];
                $factor = round($options['chakraSize'] / $this->chakraDivider);
                $transformInfo = $this->transformRules[$rashi];
                $transformInfo['transform'][Matrix::TYPE_SCALING] = [$factor, $factor];
                $matrixCoord = Matrix::getInstance(Matrix::TYPE_DEFAULT, [[$x, $y, 1]]);
                
                foreach ($transformInfo['transform'] as $transform => $params) {
                    $matrixTransform = Matrix::getInstance($transform, ...$params);
                    $matrixCoord->multiMatrix($matrixTransform);
                }
                
                $arrayCoord = $matrixCoord->toArray();
                list($x, $y) = $arrayCoord[0];
                
                $myPoints[$graha]['x'] = $x + $leftOffset;
                $myPoints[$graha]['y'] = $y + $topOffset;
                $myPoints[$graha]['textAlign'] = 'center';
                $myPoints[$graha]['textValign'] = 'middle';
                $i += 1;
            }
        }
        return $myPoints;
    }
    
    private function getCountKey($grahaCount)
    {
        if ($grahaCount == 1) {
            $countKey = self::COUNT_ONE;
        } elseif ($grahaCount > 1 && $grahaCount <= 5) {
            $countKey = self::COUNT_FIVE;
        } else {
            $countKey = self::COUNT_MORE;
        }
        
        return $countKey;
    }
}