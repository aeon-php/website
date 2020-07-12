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

    public function all() : array
    {
        return \array_merge(
            $this->calendarSources(),
            $this->calendarDoctrineSources(),
            $this->calendarTwigSources(),
            $this->calendarHolidaysSources(),
            $this->processSources(),
            $this->retrySources()
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
                            'methodSlug' => SlugGenerator::forClassMethod($method)
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
                            'methodSlug' => SlugGenerator::forClassMethod($method)
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
                            'methodSlug' => SlugGenerator::forClassMethod($method)
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
                            'methodSlug' => SlugGenerator::forClassMethod($method)
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
    private function processSources(): array
    {
        $sources = [];

        foreach ($this->processVersions() as $version => $srv) {
            $sources[] = new Source('docs_process_version', ['version' => $version]);
        }

        foreach ($this->processVersions() as $version => $srv) {
            foreach ($this->processClasses($version) as $phpClass) {
                $sources[] = new Source('docs_process_class', ['version' => $version, 'classSlug' => SlugGenerator::forPHPClass($phpClass)]);
            }
        }

        foreach ($this->processVersions() as $version => $srv) {
            foreach ($this->processClasses($version) as $phpClass) {
                foreach ($phpClass->methods() as $method) {
                    $sources[] = new Source(
                        'docs_process_class_method',
                        [
                            'version' => $version,
                            'classSlug' => SlugGenerator::forPHPClass($phpClass),
                            'methodSlug' => SlugGenerator::forClassMethod($method)
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
                            'methodSlug' => SlugGenerator::forClassMethod($method)
                        ]
                    );
                }
            }
        }

        return $sources;
    }
}
