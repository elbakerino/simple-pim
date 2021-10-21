<?php

namespace App\RouteHandler;

use App\Repository\ProductRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Satellite\KernelRoute\Annotations\Post;
use Satellite\Response\Response;

/**
 * @Post("/product", name="product--create")
 */
class ProductCreate implements RequestHandlerInterface {
    protected ProductRepository $repository;

    public function __construct(ProductRepository $repository) {
        $this->repository = $repository;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \JsonException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface {
        throw new \RuntimeException('not implemented');
        $body = $request->getParsedBody();
        return (new Response($body))->json();
    }
}
