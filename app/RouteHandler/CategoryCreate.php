<?php

namespace App\RouteHandler;

use App\Repository\CategoryRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Satellite\KernelRoute\Annotations\Post;
use Satellite\Response\Response;

/**
 * @Post("/category", name="category--create")
 */
class CategoryCreate implements RequestHandlerInterface {
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
        $body = $request->getParsedBody();
        $name = $body->name ?? null;
        if(!$name) {
            return (new Response(['error' => 'Missing `name`']))->json(400);
        }
        $category = $this->repository->create(
            $name,
            $body->parent ?? null,
            $body->position ?? 0,
            $body->labels ?? new \stdClass(),
        );
        return (new Response([
            'category' => $category,
        ]))->json();
    }
}
