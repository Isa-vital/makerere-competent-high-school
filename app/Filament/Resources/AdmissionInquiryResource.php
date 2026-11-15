<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdmissionInquiryResource\Pages;
use App\Models\ContactMessage;
use Illuminate\Database\Eloquent\Builder;

class AdmissionInquiryResource extends ContactMessageResource
{
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationGroup = 'Submissions';

    protected static ?int $navigationSort = 15;

    protected static ?string $modelLabel = 'Admission Inquiry';

    protected static ?string $pluralModelLabel = 'Admission Inquiries';

    public static function getEloquentQuery(): Builder
    {
        return ContactMessage::query()->where('subject', 'like', 'Admission Inquiry - %');
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getEloquentQuery()->where('status', 'new')->count();

        return $count ? (string) $count : null;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAdmissionInquiries::route('/'),
            'edit' => Pages\EditAdmissionInquiry::route('/{record}/edit'),
        ];
    }
}
