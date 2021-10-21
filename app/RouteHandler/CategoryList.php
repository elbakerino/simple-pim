<?php

namespace App\RouteHandler;

use App\Repository\CategoryRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Satellite\KernelRoute\Annotations\Get;
use Satellite\Response\Response;

/**
 * @Get("/categories", name="category--list")
 */
class CategoryList implements RequestHandlerInterface {
    protected CategoryRepository $repository;

    public function __construct(CategoryRepository $repository) {
        $this->repository = $repository;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \JsonException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface {
        $params = $request->getQueryParams();
        $parent = $params['parent'] ?? 'root';
        $categories = $this->repository->listByParent($parent, 'asc', 25);
        return (new Response([
            'categories' => $categories,
        ]))->json();
    }
}
