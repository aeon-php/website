<?php

declare(strict_types=1);

namespace App\StaticContent;

use App\Controller\CodeReflectionTrait;
use App\Documentation\SlugGenerator;
use NorbertTech\StaticContentGeneratorBundle\Content\Source;
use NorbertTech\StaticContentGeneratorBundle\Content\SourceProvider;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class AeonDocsSourceProvider implements SourceProvider
{
    use CodeReflectionTrait;

    private ParameterBagInterface $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    protected function parameterBag(): ParameterBagInterface
    {
        return $this->parameterBag;
    }

    /**
     * @return Source[]
     */
    public function all(): array
    {
        return \array_merge(
            $this->calendarSources(),
            $this->calendarDoctrineSources(),
            $this->calendarTwigSources(),
            $this->calendarHolidaysSources(),
            $this->calendarHolidaysYasumiSources(),
            $this->businessHoursSources(),
            $this->sleepSources(),
            $this->retrySources(),
            $this->symfonyBundleSources(),
            $this->rateLimiterSources()
        );
    }

    /**
     * @return Source[]
     */
    private function calendarSources(): array
    {
        $sources = [];

        foreach ($this->calendarVersions() as $version => $srv) {
            $sources[] = new Source('docs_calendar_version', ['version' => $version]);
        }

        foreach ($this->calendarVersions() as $version => $srv) {
            foreach ($this->calendarClasses($version) as $phpClass) {
                $sources[] = new Source('docs_calendar_class', ['version' => $version, 'classSlug' => SlugGenerator::forPHPClass($phpClass)]);
            }
        }

        foreach ($this->calendarVersions() as $version => $srv) {
            foreach ($this->calendarClasses($version) as $phpClass) {
                foreach ($phpClass->methods() as $method) {
                    $sources[] = new Source(
                        'docs_calendar_class_method',
                        [
                            'version' => $version,
                            'classSlug' => SlugGenerator::forPHPClass($phpClass),
                            'methodSlug' => SlugGenerator::forClassMethod($method),
                        ]
                    );
                }
            }
        }

        return $sources;
    }

    /**
     * @return Source[]
     */
    private function calendarDoctrineSources(): array
    {
        $sources = [];

        foreach ($this->calendarDoctrineVersions() as $version => $srv) {
            $sources[] = new Source('docs_calendar_doctrine_version', ['version' => $version]);
        }

        foreach ($this->calendarDoctrineVersions() as $version => $srv) {
            foreach ($this->calendarDoctrineClasses($version) as $phpClass) {
                $sources[] = new Source('docs_calendar_doctrine_class', ['version' => $version, 'classSlug' => SlugGenerator::forPHPClass($phpClass)]);
            }
        }

        foreach ($this->calendarDoctrineVersions() as $version => $srv) {
            foreach ($this->calendarDoctrineClasses($version) as $phpClass) {
                foreach ($phpClass->methods() as $method) {
                    $sources[] = new Source(
                        'docs_calendar_doctrine_class_method',
                        [
                            'version' => $version,
                            'classSlug' => SlugGenerator::forPHPClass($phpClass),
                            'methodSlug' => SlugGenerator::forClassMethod($method),
                        ]
                    );
                }
            }
        }

        return $sources;
    }

    /**
     * @return Source[]
     */
    private function calendarTwigSources(): array
    {
        $sources = [];

        foreach ($this->calendarTwigVersions() as $version => $srv) {
            $sources[] = new Source('docs_calendar_twig_version', ['version' => $version]);
        }

        foreach ($this->calendarTwigVersions() as $version => $srv) {
            foreach ($this->calendarTwigClasses($version) as $phpClass) {
                $sources[] = new Source('docs_calendar_twig_class', ['version' => $version, 'classSlug' => SlugGenerator::forPHPClass($phpClass)]);
            }
        }

        foreach ($this->calendarTwigVersions() as $version => $srv) {
            foreach ($this->calendarTwigClasses($version) as $phpClass) {
                foreach ($phpClass->methods() as $method) {
                    $sources[] = new Source(
                        'docs_calendar_twig_class_method',
                        [
                            'version' => $version,
                            'classSlug' => SlugGenerator::forPHPClass($phpClass),
                            'methodSlug' => SlugGenerator::forClassMethod($method),
                        ]
                    );
                }
            }
        }

        return $sources;
    }

    /**
     * @return Source[]
     */
    private function calendarHolidaysSources(): array
    {
        $sources = [];

        foreach ($this->calendarHolidaysVersions() as $version => $srv) {
            $sources[] = new Source('docs_calendar_holidays_version', ['version' => $version]);
        }

        foreach ($this->calendarHolidaysVersions() as $version => $srv) {
            foreach ($this->calendarHolidaysClasses($version) as $phpClass) {
                $sources[] = new Source('docs_calendar_holidays_class', ['version' => $version, 'classSlug' => SlugGenerator::forPHPClass($phpClass)]);
            }
        }

        foreach ($this->calendarHolidaysVersions() as $version => $srv) {
            foreach ($this->calendarHolidaysClasses($version) as $phpClass) {
                foreach ($phpClass->methods() as $method) {
                    $sources[] = new Source(
                        'docs_calendar_holidays_class_method',
                        [
                            'version' => $version,
                            'classSlug' => SlugGenerator::forPHPClass($phpClass),
                            'methodSlug' => SlugGenerator::forClassMethod($method),
                        ]
                    );
                }
            }
        }

        return $sources;
    }

    /**
     * @return Source[]
     */
    private function calendarHolidaysYasumiSources(): array
    {
        $sources = [];

        foreach ($this->calendarHolidaysYasumiVersions() as $version => $srv) {
            $sources[] = new Source('docs_calendar_holidays_yasumi_version', ['version' => $version]);
        }

        foreach ($this->calendarHolidaysYasumiVersions() as $version => $srv) {
            foreach ($this->calendarHolidaysYasumiClasses($version) as $phpClass) {
                $sources[] = new Source('docs_calendar_holidays_yasumi_class', ['version' => $version, 'classSlug' => SlugGenerator::forPHPClass($phpClass)]);
            }
        }

        foreach ($this->calendarHolidaysYasumiVersions() as $version => $srv) {
            foreach ($this->calendarHolidaysYasumiClasses($version) as $phpClass) {
                foreach ($phpClass->methods() as $method) {
                    $sources[] = new Source(
                        'docs_calendar_holidays_yasumi_class_method',
                        [
                            'version' => $version,
                            'classSlug' => SlugGenerator::forPHPClass($phpClass),
                            'methodSlug' => SlugGenerator::forClassMethod($method),
                        ]
                    );
                }
            }
        }

        return $sources;
    }

    /**
     * @return Source[]
     */
    private function businessHoursSources(): array
    {
        $sources = [];

        foreach ($this->businessHoursVersions() as $version => $srv) {
            $sources[] = new Source('docs_business_hours_version', ['version' => $version]);
        }

        foreach ($this->businessHoursVersions() as $version => $srv) {
            foreach ($this->businessHoursClasses($version) as $phpClass) {
                $sources[] = new Source('docs_business_hours_class', ['version' => $version, 'classSlug' => SlugGenerator::forPHPClass($phpClass)]);
            }
        }

        foreach ($this->businessHoursVersions() as $version => $srv) {
            foreach ($this->businessHoursClasses($version) as $phpClass) {
                foreach ($phpClass->methods() as $method) {
                    $sources[] = new Source(
                        'docs_business_hours_class_method',
                        [
                            'version' => $version,
                            'classSlug' => SlugGenerator::forPHPClass($phpClass),
                            'methodSlug' => SlugGenerator::forClassMethod($method),
                        ]
                    );
                }
            }
        }

        return $sources;
    }

    /**
     * @return Source[]
     */
    private function sleepSources(): array
    {
        $sources = [];

        foreach ($this->sleepVersions() as $version => $srv) {
            $sources[] = new Source('docs_sleep_version', ['version' => $version]);
        }

        foreach ($this->sleepVersions() as $version => $srv) {
            foreach ($this->sleepClasses($version) as $phpClass) {
                $sources[] = new Source('docs_sleep_class', ['version' => $version, 'classSlug' => SlugGenerator::forPHPClass($phpClass)]);
            }
        }

        foreach ($this->sleepVersions() as $version => $srv) {
            foreach ($this->sleepClasses($version) as $phpClass) {
                foreach ($phpClass->methods() as $method) {
                    $sources[] = new Source(
                        'docs_sleep_class_method',
                        [
                            'version' => $version,
                            'classSlug' => SlugGenerator::forPHPClass($phpClass),
                            'methodSlug' => SlugGenerator::forClassMethod($method),
                        ]
                    );
                }
            }
        }

        return $sources;
    }

    /**
     * @return Source[]
     */
    private function retrySources(): array
    {
        $sources = [];

        foreach ($this->retryVersions() as $version => $srv) {
            $sources[] = new Source('docs_retry_version', ['version' => $version]);
        }

        foreach ($this->retryVersions() as $version => $srv) {
            foreach ($this->retryClasses($version) as $phpClass) {
                $sources[] = new Source('docs_retry_class', ['version' => $version, 'classSlug' => SlugGenerator::forPHPClass($phpClass)]);
            }
        }

        foreach ($this->retryVersions() as $version => $srv) {
            foreach ($this->retryClasses($version) as $phpClass) {
                foreach ($phpClass->methods() as $method) {
                    $sources[] = new Source(
                        'docs_retry_class_method',
                        [
                            'version' => $version,
                            'classSlug' => SlugGenerator::forPHPClass($phpClass),
                            'methodSlug' => SlugGenerator::forClassMethod($method),
                        ]
                    );
                }
            }
        }

        return $sources;
    }

    /**
     * @return Source[]
     */
    private function rateLimiterSources(): array
    {
        $sources = [];

        foreach ($this->rateLimiterVersions() as $version => $srv) {
            $sources[] = new Source('docs_rate_limiter_version', ['version' => $version]);
        }

        foreach ($this->rateLimiterVersions() as $version => $srv) {
            foreach ($this->rateLimiterClasses($version) as $phpClass) {
                $sources[] = new Source('docs_rate_limiter_class', ['version' => $version, 'classSlug' => SlugGenerator::forPHPClass($phpClass)]);
            }
        }

        foreach ($this->rateLimiterVersions() as $version => $srv) {
            foreach ($this->rateLimiterClasses($version) as $phpClass) {
                foreach ($phpClass->methods() as $method) {
                    $sources[] = new Source(
                        'docs_rate_limiter_class_method',
                        [
                            'version' => $version,
                            'classSlug' => SlugGenerator::forPHPClass($phpClass),
                            'methodSlug' => SlugGenerator::forClassMethod($method),
                        ]
                    );
                }
            }
        }

        return $sources;
    }

    /**
     * @return Source[]
     */
    private function symfonyBundleSources(): array
    {
        $sources = [];

        foreach ($this->symfonyBundleVersions() as $version => $srv) {
            $sources[] = new Source('docs_symfony_bundle_version', ['version' => $version]);
        }

        foreach ($this->symfonyBundleVersions() as $version => $srv) {
            foreach ($this->symfonyBundleClasses($version) as $phpClass) {
                $sources[] = new Source('docs_symfony_bundle_class', ['version' => $version, 'classSlug' => SlugGenerator::forPHPClass($phpClass)]);
            }
        }

        foreach ($this->symfonyBundleVersions()  as $version => $srv) {
            foreach ($this->symfonyBundleClasses($version) as $phpClass) {
                foreach ($phpClass->methods() as $method) {
                    $sources[] = new Source(
                        'docs_symfony_bundle_class_method',
                        [
                            'version' => $version,
                            'classSlug' => SlugGenerator::forPHPClass($phpClass),
                            'methodSlug' => SlugGenerator::forClassMethod($method),
                        ]
                    );
                }
            }
        }

        return $sources;
    }
}
