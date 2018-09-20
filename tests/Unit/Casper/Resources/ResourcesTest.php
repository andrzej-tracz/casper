<?php

namespace Tests\Unit\Casper\Resources;

use App\Casper\Model;
use Illuminate\Database\Eloquent\Model as BaseModel;
use App\Http\Resources;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Tests\TestCase;

/**
 * Class ResourcesTest
 * @package Tests\Unit\Casper\Resources
 *
 * @group resources
 */
class ResourcesTest extends TestCase
{
    use DatabaseTransactions;

    protected $resources = [
        Model\User::class => Resources\User::class,
        Model\Event::class => Resources\Event::class,
        Model\Guest::class => Resources\Guest::class,
        Model\EventInvitation::class => Resources\EventInvitation::class,
    ];

    /**
     * @test
     */
    public function it_transforms_to_resources()
    {
        $request = $this->createMock(Request::class);

        foreach ($this->resources as $model => $resource) {
            $model = factory($model)->create();
            $this->assertTrue($model instanceof BaseModel);

            /** @var $transformed  JsonResource */
            $transformed = new $resource($model);
            $raw = $transformed->toArray($request);
            $this->assertTrue(is_array($raw));
            $response = $transformed->toResponse($request);
            $wrapping = $resource::$wrap;

            /** @noinspection PhpComposerExtensionStubsInspection */
            $this->assertEquals($response->content(), json_encode([
                $wrapping => $raw
            ]));
        }
    }
}
