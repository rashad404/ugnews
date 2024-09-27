<?php
namespace Models;

use Core\Model;
use Core\Language;

class SitemapModel extends Model
{
    private $newsTable = 'news';
    private $channelsTable = 'channels';
    private $tagsTable = 'tags';
    private $limit = 50000; // Maximum URLs per sitemap file
    private $website;
    private $lng;

    const SITEMAP_NS = 'http://www.sitemaps.org/schemas/sitemap/0.9';

    public function __construct()
    {
        parent::__construct();
        $this->website = 'https://' . SITE_URL;
        $this->lng = new Language();
        $this->lng->load('app');
    }

    /**
     * Main method to update the sitemap
     */
    public function update()
    {
        // Retrieve static and dynamic links
        $staticLinks = $this->getStaticLinks();
        $channelLinks = $this->fetchChannelLinks();
        $newsLinks = $this->fetchNewsLinks();
        // $tagLinks = $this->fetchTagLinks();

        // Merge all links into one array
        // $allLinks = array_merge($staticLinks, $newsLinks, $channelLinks, $tagLinks);
        $allLinks = array_merge($staticLinks, $channelLinks, $newsLinks);

        // Ensure uniqueness based on URL
        $uniqueLinks = [];
        foreach ($allLinks as $link) {
            $uniqueLinks[$link['url']] = $link;
        }
        $allLinks = array_values($uniqueLinks);

        $totalLinks = count($allLinks);
        $sitemapCount = ceil($totalLinks / $this->limit);
        $sitemaps = [];

        for ($i = 0; $i < $sitemapCount; $i++) {
            $chunk = array_slice($allLinks, $i * $this->limit, $this->limit);
            $sitemapFilename = 'sitemap' . ($i + 1) . '.xml';
            $filePath = 'sitemaps/' . SITE_FOLDER . '/' . $sitemapFilename;

            $this->generateSitemapFile($chunk, $filePath);
            $sitemaps[] = [
                'loc'     => $this->website . '/sitemaps/' . SITE_FOLDER . '/' . $sitemapFilename,
                'lastmod' => date(DATE_W3C),
            ];
        }

        $this->generateSitemapIndex($sitemaps);

        // Optional: Notify search engines
        // $this->pingSearchEngines($this->website . '/sitemaps/' . SITE_FOLDER . '/sitemap_index.xml');
    }

    /**
     * Get static links with custom changefreq and priority
     *
     * @return array
     */
    private function getStaticLinks()
    {
        return [
            [
                'url'        => $this->website,
                'changefreq' => 'hourly',
                'priority'   => '1.0',
            ],
            [
                'url'        => $this->website . '/tags/valyuta',
                'changefreq' => 'daily',
                'priority'   => '0.8',
            ],
            [
                'url'        => $this->website . '/tags/hava',
                'changefreq' => 'daily',
                'priority'   => '0.8',
            ],
            [
                'url'        => $this->website . '/tags/namaz',
                'changefreq' => 'daily',
                'priority'   => '0.8',
            ],
            // Add more static links here with custom changefreq and priority as needed

        ];
    }

    /**
     * Fetch dynamic news links from the database with default changefreq and priority
     *
     * @return array
     */
    private function fetchNewsLinks()
    {
        $links = [];
        $offset = 0;
        $batchSize = 1000; // Adjust batch size as needed

        do {
            $query = "SELECT `slug`, `time` FROM `{$this->newsTable}` WHERE `status` = 1 ORDER BY `id` ASC LIMIT :offset, :limit";
            $params = [
                ':offset' => $offset,
                ':limit'  => $batchSize,
            ];
            $array = self::$db->select($query, $params);
            foreach ($array as $item) {
                $links[] = [
                    'url'        => $this->website . '/' . htmlspecialchars($item['slug'], ENT_QUOTES, 'UTF-8'),
                    'changefreq' => 'daily', // Default value for news articles
                    'priority'   => '0.80',  // Default value for news articles
                    'lastmod'    => date(DATE_W3C, $item['time']),
                ];
            }
            $offset += $batchSize;
        } while (count($array) == $batchSize);

        return $links;
    }

    /**
     * Fetch dynamic channel links from the database with default changefreq and priority
     *
     * @return array
     */
    private function fetchChannelLinks()
    {
        $links = [];
        $offset = 0;
        $batchSize = 1000; // Adjust batch size as needed

        do {
            $query = "SELECT `name_url`, `time` FROM `{$this->channelsTable}` WHERE `status` = 1 ORDER BY `id` ASC LIMIT :offset, :limit";
            $params = [
                ':offset' => $offset,
                ':limit'  => $batchSize,
            ];
            $array = self::$db->select($query, $params);
            foreach ($array as $item) {
                $links[] = [
                    'url'        => $this->website . '/' . htmlspecialchars($item['name_url'], ENT_QUOTES, 'UTF-8'),
                    'changefreq' => 'weekly', // Default value for channels
                    'priority'   => '0.70',  // Default value for channels
                    'lastmod'    => date(DATE_W3C, $item['time']),
                ];
            }
            $offset += $batchSize;
        } while (count($array) == $batchSize);

        return $links;
    }

    /**
     * Fetch dynamic tag links from the database with default changefreq and priority
     *
     * @return array
     */
    private function fetchTagLinks()
    {
        $links = [];
        $offset = 0;
        $batchSize = 1000; // Adjust batch size as needed

        do {
            $query = "SELECT `name` FROM `{$this->tagsTable}` WHERE `status` = 1 ORDER BY `id` ASC LIMIT :offset, :limit";
            $params = [
                ':offset' => $offset,
                ':limit'  => $batchSize,
            ];
            $array = self::$db->select($query, $params);
            foreach ($array as $item) {
                $links[] = [
                    'url'        => $this->website . '/tags/' . htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'),
                    'changefreq' => 'weekly', // Default value for tags
                    'priority'   => '0.70',  // Default value for tags
                    // 'lastmod' can be added if there's a relevant timestamp
                ];
            }
            $offset += $batchSize;
        } while (count($array) == $batchSize);

        return $links;
    }

    /**
     * Generate a single sitemap XML file
     *
     * @param array  $links    Array of links with their attributes
     * @param string $filePath Path to save the sitemap XML
     */
    private function generateSitemapFile($links, $filePath)
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $urlset = $dom->createElement('urlset');
        $urlset->setAttribute('xmlns', self::SITEMAP_NS);
        $dom->appendChild($urlset);

        foreach ($links as $linkData) {
            $url = $dom->createElement('url');

            // <loc>
            $loc = $dom->createElement('loc', $linkData['url']);
            $url->appendChild($loc);

            // <lastmod> (optional)
            // if (isset($linkData['lastmod'])) {
            //     $lastmod = $dom->createElement('lastmod', $linkData['lastmod']);
            //     $url->appendChild($lastmod);
            // }

            // <changefreq>
            if (isset($linkData['changefreq'])) {
                $changefreq = $dom->createElement('changefreq', $linkData['changefreq']);
                $url->appendChild($changefreq);
            }

            // <priority>
            if (isset($linkData['priority'])) {
                $priority = $dom->createElement('priority', $linkData['priority']);
                $url->appendChild($priority);
            }

            $urlset->appendChild($url);
        }

        $this->saveXmlFile($dom, $filePath);
    }

    /**
     * Generate the sitemap index XML file
     *
     * @param array $sitemaps Array of sitemap files with their locations and last modification dates
     */
    private function generateSitemapIndex($sitemaps)
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $sitemapindex = $dom->createElement('sitemapindex');
        $sitemapindex->setAttribute('xmlns', self::SITEMAP_NS);
        $dom->appendChild($sitemapindex);

        foreach ($sitemaps as $sitemap) {
            $sitemapElement = $dom->createElement('sitemap');

            // <loc>
            $loc = $dom->createElement('loc', htmlspecialchars($sitemap['loc'], ENT_QUOTES, 'UTF-8'));
            $sitemapElement->appendChild($loc);

            // <lastmod>
            $lastmod = $dom->createElement('lastmod', $sitemap['lastmod']);
            $sitemapElement->appendChild($lastmod);

            $sitemapindex->appendChild($sitemapElement);
        }

        $filePath = 'sitemaps/' . SITE_FOLDER . '/sitemap_index.xml';
        $this->saveXmlFile($dom, $filePath);
    }

    /**
     * Save the DOMDocument to a file with error handling
     *
     * @param \DOMDocument $dom      The DOMDocument object
     * @param string        $filePath The file path to save the XML
     */
    private function saveXmlFile($dom, $filePath)
    {
        try {
            $directory = dirname($filePath);

            if (!is_dir($directory)) {
                if (!mkdir($directory, 0777, true) && !is_dir($directory)) {
                    throw new \RuntimeException(sprintf('Directory "%s" was not created', $directory));
                }
            }

            if (!$dom->save($filePath)) {
                throw new \RuntimeException(sprintf('Failed to save XML file to "%s"', $filePath));
            }
        } catch (\Exception $e) {
            error_log('Error saving XML file: ' . $e->getMessage());
        }
    }

    /**
     * Optional: Notify search engines about the updated sitemap
     *
     * @param string $sitemapUrl The URL of the sitemap index
     */
    private function pingSearchEngines($sitemapUrl)
    {
        $engines = [
            'http://www.google.com/webmasters/sitemaps/ping?sitemap=',
            'http://www.bing.com/webmaster/ping.aspx?siteMap=',
        ];

        foreach ($engines as $engine) {
            $url = $engine . urlencode($sitemapUrl);
            @file_get_contents($url);
        }
    }
}