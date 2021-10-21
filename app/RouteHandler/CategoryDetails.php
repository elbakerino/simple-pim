<?php

namespace App\RouteHandler;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Satellite\KernelRoute\Annotations\Get;
use Satellite\KernelRoute\Annotations\Post;
use Satellite\Response\Response;

/**
 * @Get("/category/{category}", name="category--details")
 */
class CategoryDetails implements RequestHandlerInterface {
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
        $uuid = $request->getAttribute('category');
        if(!$uuid) {
            return (new Response(['error' => 'category-param-must-be-set']))->json(400);
        }
        try {
            $category = $this->repository->getDetails($uuid);
        } catch(\RuntimeException $e) {
            if($e->getMessage() === 'db entry not found') {
                return (new Response(['error' => $e->getMessage()]))->json(404);
            }
            throw $e;
        }
        return (new Response([
            'category' => $category,
        ]))->json();
    }
}
