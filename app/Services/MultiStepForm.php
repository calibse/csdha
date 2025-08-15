<?php

namespace App\Services;

use App\Models\Event;

class MultiStepForm
{
    protected int $currentStep;
    protected ?int $nextStep;
    protected ?int $previousStep;
    protected static array $steps;
    protected static int $firstStep = 0;
    protected static int $lastStep;
    protected static array $routes; 
    protected static string $startRoute = '';
    protected static string $endRoute = '';
    protected static string $endView = '';
    protected static string $sessionInputName = '';
    protected static array $viewsData;
    protected static array $endViewData;
    protected static string $formTitle;

    public function __construct(string $routeName)
    {
        self::boot();
        $routeName = self::cleanRouteName($routeName);
        $i = array_search($routeName, self::$steps);
        if ($i === false) {
            throw new InvalidArgumentException('Invalid route name.');
        }
        $this->currentStep = $i;
        $this->nextStep = $this->currentStep !== self::$lastStep 
            ? $this->currentStep + 1 : null;
        $this->previousStep = $this->currentStep !== 0 
            ? $this->currentStep - 1 : null;
    }

    public function routeName(): string
    {
        return self::$steps[$this->currentStep];
    }

    public function nextStepRouteName(): ?string
    {
        return $this->nextStep !== null ? self::$steps[$this->nextStep] : null;
    }

    public function previousStepRouteName(): ?string
    {
        return $this->previousStep !== null ? self::$steps[$this->previousStep] : null;
    }

    public function view(): string
    {  
        return self::$routes[self::$steps[$this->currentStep]]['view'];
    }

    public function rules(): array
    {  
        return self::$routes[self::$steps[$this->currentStep]]['rules'];
    }

    public function inputs(): array
    {  
        return self::$routes[self::$steps[$this->currentStep]]['inputs'];
    }

    public function isFirstStep(): bool
    {  
        return $this->currentStep === self::$firstStep ? true : false;
    }

    public function isLastStep(): bool
    {  
        return $this->currentStep === self::$lastStep ? true : false;
    }

    public static function cleanRouteName($routeName): string 
    {
        $str = $routeName; 
        $substr = explode('.', $str);
        if (end($substr) === 'create') {
            array_pop($substr);
        }
        $result = implode('.', $substr);
        return $result;
    }

    public static function boot(): void
    {
        static::setRoutes();
        static::setViewsData();
        foreach (self::$routes as &$route) {
            $route['inputs'] = array_keys($route['rules']);
        }
        unset($route);
        self::$steps = array_keys(self::$routes);
        self::$lastStep = count(self::$steps) - 1;
    }

    protected static function setRoutes(): void
    {
        self::$routes = [
            [
                'view' => '',
                'rules' => []
            ]
        ];
    }

    public static function firstStepRouteName(): string
    {
        self::boot();
        $firstStep = 0;
        $steps = array_keys(self::$routes);
        return $steps[$firstStep];
    }

    public static function lastStepRouteName(): string
    {
        self::boot();
        $steps = array_keys(self::$routes);
        $lastStep = count($steps) - 1;
        return $steps[$lastStep];
    }

    public static function startRoute(): string
    {
        return static::$startRoute;
    }

    public static function endRoute(): string
    {
        return static::$endRoute;
    }

    public static function endView(): string
    {
        return static::$endView;
    }

    public static function sessionInputName(): string
    {
        return static::$sessionInputName;
    }

    protected static function setViewsData(): void
    {
        static::$viewsData = [];
    }

    public static function viewsData(): array
    {
        return static::$viewsData;
    }

    protected static function setEndViewData(): void
    {
        static::$endViewData = [];
    }

    public static function endViewData(): array
    {
        return static::$endViewData;
    }

    public static function store(Event $event): void
    {
        static::setEndViewData();
    }

}
