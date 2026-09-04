import json

from django.http import JsonResponse
from django.views.decorators.csrf import csrf_exempt
from django.views.decorators.http import require_POST

from . import rules


@csrf_exempt
@require_POST
def check_requirements(request):
    """
    POST /api/check/
    Internal server-to-server endpoint called by the PHP application after
    a client submits a Document Request or Land Transfer form. CSRF is
    exempted here because this is a trusted local service-to-service call,
    not a browser-facing endpoint — in a real deployment this should sit
    behind a private network / API key, not be exposed publicly.
    """
    try:
        payload = json.loads(request.body.decode("utf-8"))
    except (ValueError, UnicodeDecodeError):
        return JsonResponse({"error": "Invalid JSON body."}, status=400)

    if payload.get("flow") not in ("docreq", "landtransfer"):
        return JsonResponse({"error": "flow must be 'docreq' or 'landtransfer'."}, status=400)

    result = rules.evaluate(payload)
    return JsonResponse(result)


def reference_data(request):
    """GET /api/reference/ — lets the PHP layer stay in sync with the
    canonical document types / purposes / transfer types / land-transfer
    document list defined in rules.py, instead of duplicating the lists."""
    return JsonResponse({
        "document_types": rules.DOC_TYPES,
        "purposes": rules.PURPOSES,
        "transfer_types": rules.TRANSFER_TYPES,
        "land_transfer_docs": rules.LAND_TRANSFER_DOCS,
    })


def healthcheck(request):
    return JsonResponse({"status": "ok", "service": "smart-assess-ai-checker"})
