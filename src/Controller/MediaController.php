<?php

namespace App\Controller;

use App\Entity\Media\File;
use Gumlet\ImageResize;
use Gumlet\ImageResizeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation as Http;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class MediaController
 */
class MediaController extends AbstractController
{
    /**
     * @param Http\Request $request
     *
     * @return Http\JsonResponse
     */
    public function upload(Http\Request $request): Http\JsonResponse
    {
        $uploadPath = $this->getUploadPath();

        /* @var $file Http\File\UploadedFile */
        $file = $request->files->get('file');

        if ($file) {
            $uploadName = uniqid('upload_');
            $file->move($uploadPath, $uploadName);

            $response = [
                'name' => $file->getClientOriginalName(),
                'type' => $file->getClientMimeType(),
                'src' => $uploadPath . '/' . $uploadName,
            ];

            return $this->json($response);
        }

        throw new Http\File\Exception\UploadException('Could not upload file');
    }

    /**
     * @param File $file
     *
     * @return Http\Response
     * @throws ImageResizeException
     */
    public function preview(File $file): Http\Response
    {
        $previewDir = join('/', [$this->getParameter('media_dir'), '.preview']);
        $previewName = join('/', [
            $previewDir,
            $file->getContext()->getSlug(),
            $file->getStoredName()
        ]);

        if (!file_exists($previewName)) {
            $pathName = join('/', [
                $this->getParameter('media_dir'),
                $file->getContext()->getSlug(),
                $file->getStoredName()
            ]);

            if (!file_exists(dirname($previewName))) {
                mkdir(dirname($previewName), 0755, true);
            }

            $image = new ImageResize($pathName);
            $image->resizeToBestFit(400, 300);
            $image->save($previewName);
        }

        $encodedData = base64_encode(file_get_contents($previewName));
        return new Http\Response(
            'data:' . $file->getMimeType() . ';base64,' . $encodedData
        );
    }

    /**
     * @param File $file
     *
     * @return Http\Response
     */
    public function download(File $file): Http\Response
    {
        $fileName = join('/', [
            $this->getParameter('media_dir'),
            $file->getContext()->getSlug(),
            $file->getStoredName()
        ]);

        if (!file_exists($fileName)) {
            throw new NotFoundHttpException('Requested file not found');
        }

        return $this->file($fileName);
    }

    /**
     * @param File $file
     *
     * @return Http\Response
     */
    public function delete(File $file): Http\Response
    {
        $identifier = $file->getUuid();

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($file);
        $manager->flush();

        return $this->json(
            $manager->find(File::class, $identifier)
        );
    }

    /**
     * @return string
     */
    private function getUploadPath(): string
    {
        return rtrim($this->getParameter('uploads_dir'), '/');
    }
}
