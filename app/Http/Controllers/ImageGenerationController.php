<?php

namespace App\Http\Controllers;

use App\Http\Requests\GeneratePromptRequest;
use App\Http\Resources\ImageGenerationResource;
use App\Services\OpenAiService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ImageGenerationController extends Controller
{

public function __construct(private OpenAiService $openAiService)
{
    
}
    /**
     * List the authenticated user's image generations.
     *
     * Returns the latest generations in a paginated collection.
     */
    public function index()
    {
        //
        $user = request()->user();
        $imageGeneration = $user->imageGenerations()->latest()->paginate(10);
        return ImageGenerationResource::collection($imageGeneration);
    }

    /**
     * Generate a descriptive prompt from an uploaded image.
     *
     * Stores the image and returns its generated prompt and metadata.
     */
    public function store(GeneratePromptRequest $request)
    {
        //
        $user = $request->user();
        $image = $request->file('image');
        $originalName = $image->getClientOriginalName();
        $sanitizedName = preg_replace('/[^a-zA-Z0-9._-]/', '_', pathinfo($originalName, PATHINFO_FILENAME));
        $extension = $image->getClientOriginalExtension();
        $safeFilename = $sanitizedName . '_' . Str::random(10) . '.' .  $extension;
        $imagePath = $image->storeAs('uploads/images', $safeFilename, 'public');
        $generatedPrompt = $this->openAiService->generatePromptFromImage($image);

        $imageGeneration  = $user->imageGenerations()->create([
            'image_path' => $imagePath,
            'generated_prompt' => $generatedPrompt,
            'original_filename' => $originalName,
            'file_size' => $image->getSize(),
            'mime_type' => $image->getMimeType(),


        ]);
        return new ImageGenerationResource($imageGeneration);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
